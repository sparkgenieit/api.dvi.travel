// REPLACE-WHOLE-FILE
// FILE: src/modules/itineraries/engines/itinerary-vehicles.engine.ts

import { Injectable } from "@nestjs/common";
import { PrismaService } from "../../../prisma.service";
import * as fs from "fs";
import * as path from "path";

function toNum(v: any) {
  const n = typeof v === "number" ? v : Number(String(v ?? "").trim());
  return Number.isFinite(n) ? n : 0;
}

function monthName(d: Date) {
  return d.toLocaleString("en-US", { month: "long" }); // PHP date('F')
}

function safeDate(v: any): Date | null {
  if (!v) return null;
  if (v instanceof Date) return v;
  const d = new Date(v);
  return Number.isFinite(d.getTime()) ? d : null;
}

function startOfDay(d: Date) {
  const x = new Date(d);
  x.setHours(0, 0, 0, 0);
  return x;
}

function hhmmFromMs(ms: number) {
  const total = Math.max(0, ms);
  const hh = Math.floor(total / 3600000);
  const mm = Math.floor((total % 3600000) / 60000);
  return `${hh}.${String(mm).padStart(2, "0")}`; // PHP-like "H.i"
}

// Global logging flag (PHP-style debug on/off via env)
const ENABLE_LOG =
  process.env.ENABLE_LOG === "1" ||
  process.env.ENABLE_LOG === "true" ||
  process.env.ENABLE_LOG === "yes";

// ---------------------------------------------------------------------------
// PHP SUM(CASE WHEN total_vehicle_qty=0 THEN 1 ELSE total_vehicle_qty END)
// = SUM(total_vehicle_qty) + COUNT(total_vehicle_qty=0)
// ---------------------------------------------------------------------------
async function getPhpTotalVehicleQty(tx: any, whereBase: any): Promise<number> {
  const [sumAgg, zeroCount] = await Promise.all([
    tx.dvi_itinerary_plan_vendor_eligible_list.aggregate({
      where: whereBase,
      _sum: { total_vehicle_qty: true },
    }),
    tx.dvi_itinerary_plan_vendor_eligible_list.count({
      where: { ...whereBase, total_vehicle_qty: 0 },
    }),
  ]);

  console.log(
    "[vehiclesEngine] PHP_QTY_AGG",
    JSON.stringify({ whereBase, sumAgg, zeroCount }),
  );

  const sumVal = Number(sumAgg?._sum?.total_vehicle_qty ?? 0);
  return sumVal + Number(zeroCount ?? 0);
}

@Injectable()
export class ItineraryVehiclesEngine {
  private readonly LOG_DIR = path.resolve(process.cwd(), "logs");
  private readonly LOG_FILE = path.join(this.LOG_DIR, "vehicles-engine.log");

  constructor(private readonly prisma: PrismaService) {}

  // ---------------------------------------------------------------------------
  // FILE LOGGING
  // ---------------------------------------------------------------------------
  private writeLog(line: string) {
    if (!ENABLE_LOG) return;
    try {
      if (!fs.existsSync(this.LOG_DIR)) {
        fs.mkdirSync(this.LOG_DIR, { recursive: true });
      }
      fs.appendFileSync(this.LOG_FILE, line + "\n", { encoding: "utf8" });
    } catch (err) {
      // fallback to console if file logging fails
      console.error("[vehiclesEngine] LOG FILE ERROR", err);
    }
  }

  private escapeString(value: string): string {
    return value.replace(/'/g, "''");
  }

  private sqlLiteral(value: any): string {
    if (value === null || value === undefined) return "NULL";
    if (value instanceof Date) {
      const iso = value.toISOString().slice(0, 19).replace("T", " ");
      return `'${iso}'`;
    }
    if (typeof value === "number") {
      if (!Number.isFinite(value)) return "NULL";
      return String(value);
    }
    if (typeof value === "boolean") {
      return value ? "1" : "0";
    }
    return `'${this.escapeString(String(value))}'`;
  }

  private sqlList(values: any[]): string {
    if (!values || !values.length) return "(NULL)";
    return "(" + values.map((v) => this.sqlLiteral(v)).join(", ") + ")";
  }

  // build WHERE clause from Prisma-like where object
  private buildWhereClause(where: any): string {
    if (!where || Object.keys(where).length === 0) return "1=1";

    const build = (obj: any): string => {
      if (!obj || typeof obj !== "object") return "1=1";

      const parts: string[] = [];

      for (const key of Object.keys(obj)) {
        if (key === "AND" && Array.isArray(obj[key])) {
          const inner = obj[key].map((w: any) => `(${build(w)})`).join(" AND ");
          if (inner) parts.push(inner);
          continue;
        }
        if (key === "OR" && Array.isArray(obj[key])) {
          const inner = obj[key].map((w: any) => `(${build(w)})`).join(" OR ");
          if (inner) parts.push(inner);
          continue;
        }
        if (key === "NOT" && Array.isArray(obj[key])) {
          const inner = obj[key].map((w: any) => `NOT (${build(w)})`).join(" AND ");
          if (inner) parts.push(inner);
          continue;
        }

        const value = obj[key];

        if (value && typeof value === "object" && !Array.isArray(value) && !(value instanceof Date)) {
          if (value.in) {
            parts.push(`\`${key}\` IN ${this.sqlList(value.in)}`);
          } else if (value.notIn) {
            parts.push(`\`${key}\` NOT IN ${this.sqlList(value.notIn)}`);
          } else if (value.contains !== undefined) {
            parts.push(`\`${key}\` LIKE ${this.sqlLiteral(`%${value.contains}%`)}`);
          } else if (value.gte !== undefined) {
            parts.push(`\`${key}\` >= ${this.sqlLiteral(value.gte)}`);
          } else if (value.gt !== undefined) {
            parts.push(`\`${key}\` > ${this.sqlLiteral(value.gt)}`);
          } else if (value.lte !== undefined) {
            parts.push(`\`${key}\` <= ${this.sqlLiteral(value.lte)}`);
          } else if (value.lt !== undefined) {
            parts.push(`\`${key}\` < ${this.sqlLiteral(value.lt)}`);
          } else if (value.equals !== undefined) {
            parts.push(`\`${key}\` = ${this.sqlLiteral(value.equals)}`);
          } else {
            parts.push(`/* ${key} = ${JSON.stringify(value)} */ 1=1`);
          }
        } else {
          parts.push(`\`${key}\` = ${this.sqlLiteral(value)}`);
        }
      }

      if (!parts.length) return "1=1";
      return parts.join(" AND ");
    };

    return build(where);
  }

  private buildSelectSql(table: string, where: any, extra?: string): string {
    const whereClause = this.buildWhereClause(where);
    const tail = extra ? ` ${extra.trim()}` : "";
    return `SELECT * FROM \`${table}\` WHERE ${whereClause}${tail};`;
  }

  private buildDeleteSql(table: string, where: any): string {
    const whereClause = this.buildWhereClause(where);
    return `DELETE FROM \`${table}\` WHERE ${whereClause};`;
  }

  private buildUpdateSql(table: string, data: any, where: any): string {
    const setParts: string[] = [];
    for (const key of Object.keys(data || {})) {
      if (data[key] === undefined) continue;
      setParts.push(`\`${key}\` = ${this.sqlLiteral(data[key])}`);
    }
    const setClause = setParts.length ? setParts.join(", ") : "/* no fields */ 1=1";
    const whereClause = this.buildWhereClause(where);
    return `UPDATE \`${table}\` SET ${setClause} WHERE ${whereClause};`;
  }

  private buildInsertSql(table: string, data: any): string {
    const keys = Object.keys(data || {});
    if (!keys.length) return `/* EMPTY INSERT for ${table} */`;
    const cols = keys.map((k) => `\`${k}\``).join(", ");
    const vals = keys.map((k) => this.sqlLiteral(data[k])).join(", ");
    return `INSERT INTO \`${table}\` (${cols}) VALUES (${vals});`;
  }

  private logSql(label: string, sql: string, meta?: any) {
    if (!ENABLE_LOG) return;
    const line =
      `[${new Date().toISOString()}] [vehiclesEngine] ${label}\n` +
      `SQL: ${sql}\n` +
      (meta ? `META: ${JSON.stringify(meta)}\n` : "");
    console.log(line);
    this.writeLog(line);
  }

  private log(label: string, payload: any) {
    if (!ENABLE_LOG) return;
    const line =
      `[${new Date().toISOString()}] [vehiclesEngine] DEBUG ${label} ` +
      JSON.stringify(payload);
    console.log(line);
    this.writeLog(line);
  }

  // ---------------------------------------------------------------------------
  // ROUTE KM SUMMARY (PHP-style helper; uses route.no_of_km ONLY)
  // ---------------------------------------------------------------------------
  private async buildRouteKmMap(
    tx: any,
    planId: number,
    routes: { itinerary_route_ID: number; no_of_km: string | null }[],
  ): Promise<Map<number, number>> {
    const routeKmMap = new Map<number, number>();

    for (const r of routes) {
      const routeId = Number(r.itinerary_route_ID ?? 0);
      if (!routeId) continue;
      const km = toNum(r.no_of_km);
      if (km > 0) {
        routeKmMap.set(routeId, km);
      }
    }

    this.log("ROUTE_KM_MAP_BUILT", Object.fromEntries(routeKmMap));

    return routeKmMap;
  }

  /**
   * Rebuilds:
   *   1) dvi_itinerary_plan_vendor_eligible_list
   *   2) dvi_itinerary_plan_vendor_vehicle_details
   *
   * Behaviour aligned with PHP `add_vehicle_plan`:
   * - Build vendor eligibles
   * - Mark cheapest per vehicle type as assigned
   * - Build vendor_vehicle_details ONLY for assigned eligibles
   */
  async rebuildEligibleVendorList(args: { planId: number; createdBy: number }) {
    const planId = Number(args.planId);
    const createdBy = Number(args.createdBy ?? 0);

    if (!Number.isFinite(planId) || planId <= 0) {
      return { planId, inserted: 0, reason: "Invalid planId" };
    }

    this.log("INPUT_ARGS", { planId, createdBy });

    // use plain client (no $transaction) for now
    const tx: any = this.prisma;
    const tAny: any = this.prisma as any;

    const today = startOfDay(new Date());

    // ---------------------------------------------------------------------
    // 0) Plan
    // ---------------------------------------------------------------------
    const planWhere = { itinerary_plan_ID: planId };
    this.logSql(
      "PLAN_FIND_UNIQUE",
      this.buildSelectSql("dvi_itinerary_plan_details", planWhere, "LIMIT 1"),
      { where: planWhere },
    );

    const plan = await tx.dvi_itinerary_plan_details.findUnique({
      where: planWhere,
      select: {
        itinerary_plan_ID: true,
        itinerary_type: true,
        pick_up_date_and_time: true,
        trip_start_date_and_time: true,
        trip_end_date_and_time: true,
        no_of_days: true,
      },
    });
    this.log("PLAN_FETCHED", plan);

    if (!plan) return { planId, inserted: 0, reason: "Plan not found" };

    // ---------------------------------------------------------------------
    // 1) Routes summary
    // ---------------------------------------------------------------------
    const routesWhere = { itinerary_plan_ID: planId, status: 1, deleted: 0 };
    this.logSql(
      "ROUTES_FIND_MANY",
      this.buildSelectSql(
        "dvi_itinerary_route_details",
        routesWhere,
        "ORDER BY `itinerary_route_ID` ASC",
      ),
      { where: routesWhere },
    );

    const routes = await tx.dvi_itinerary_route_details.findMany({
      where: routesWhere,
      select: {
        itinerary_route_ID: true,
        itinerary_route_date: true,
        no_of_km: true,
        location_name: true,
        next_visiting_location: true,
      },
      orderBy: { itinerary_route_ID: "asc" },
    });
    this.log("ROUTES_FETCHED", { count: routes.length, routes });

    const locationTokens = Array.from(
      new Set(
        routes
          .flatMap((r: any) => [
            String((r as any).location_name ?? "").trim(),
            String((r as any).next_visiting_location ?? "").trim(),
          ])
          .filter((v: string) => v.length > 0),
      ),
    );
    this.log("ROUTE_LOCATION_TOKENS", locationTokens);

    // ---------------------------------------------------------------------
    // 1.1) Build eligible cities from dvi_stored_locations (PHP UNION parity)
    // ---------------------------------------------------------------------
    let eligibleCities: string[] = [];
    if (locationTokens.length) {
      // 1️⃣ source_location side
      const storedSrcWhere = {
        deleted: 0,
        status: 1,
        source_location: { in: locationTokens },
      };
      this.logSql(
        "STORED_LOCATIONS_SRC_FIND_MANY",
        this.buildSelectSql(
          "dvi_stored_locations",
          storedSrcWhere,
        ),
        { where: storedSrcWhere },
      );

      const storedSrcRows = await tx.dvi_stored_locations.findMany({
        where: storedSrcWhere,
        select: {
          source_location_city: true,
        },
      });
      this.log("STORED_LOCATIONS_SRC_FETCHED", storedSrcRows);

      // 2️⃣ destination_location side
      const storedDestWhere = {
        deleted: 0,
        status: 1,
        destination_location: { in: locationTokens },
      };
      this.logSql(
        "STORED_LOCATIONS_DEST_FIND_MANY",
        this.buildSelectSql(
          "dvi_stored_locations",
          storedDestWhere,
        ),
        { where: storedDestWhere },
      );

      const storedDestRows = await tx.dvi_stored_locations.findMany({
        where: storedDestWhere,
        select: {
          destination_location_city: true,
        },
      });
      this.log("STORED_LOCATIONS_DEST_FETCHED", storedDestRows);

      const citySet = new Set<string>();

      for (const row of storedSrcRows) {
        const s = String(row.source_location_city ?? "").trim();
        if (s) citySet.add(s);
      }

      for (const row of storedDestRows) {
        const d = String(row.destination_location_city ?? "").trim();
        if (d) citySet.add(d);
      }

      eligibleCities = Array.from(citySet);
    }

    this.log("ELIGIBLE_CITIES", eligibleCities);

    const eligibleCityTokensLower = eligibleCities.map((c) => c.toLowerCase());

    // PHP: total km is sum of route.no_of_km ONLY
    const totalKmsNum = routes.reduce(
      (sum: number, r: { no_of_km: string | null }) => sum + toNum(r.no_of_km),
      0,
    );

    const totalOutstationKmNum = totalKmsNum;

    const tripStart = safeDate(plan.trip_start_date_and_time);
    const tripEnd = safeDate(plan.trip_end_date_and_time);
    const totalTimeStr =
      tripStart && tripEnd ? hhmmFromMs(tripEnd.getTime() - tripStart.getTime()) : "0.00";

    const routeDateBase =
      safeDate(plan.pick_up_date_and_time) ||
      safeDate(routes?.[0]?.itinerary_route_date) ||
      tripStart ||
      new Date();

    const yearStr = String(routeDateBase.getFullYear());
    const monthStr = monthName(routeDateBase);
    const dayOfMonth = Math.max(1, Math.min(31, routeDateBase.getDate()));
    const noOfDays = Math.max(1, Number(plan.no_of_days ?? 1) || 1);

    const totalNoOfPlanRouteDetails = Math.max(0, routes.length);

    // ---------------------------------------------------------------------
    // 2) Required vehicle entries from plan
    // ---------------------------------------------------------------------
    const reqWhere = { itinerary_plan_id: planId, status: 1, deleted: 0 };
    this.logSql(
      "PLAN_VEHICLE_DETAILS_FIND_MANY",
      this.buildSelectSql("dvi_itinerary_plan_vehicle_details", reqWhere),
      { where: reqWhere },
    );

    const reqRows = await tx.dvi_itinerary_plan_vehicle_details.findMany({
      where: reqWhere,
      select: { vehicle_type_id: true, vehicle_count: true },
    });
    this.log("PLAN_VEHICLE_DETAILS_FETCHED", reqRows);

    if (!reqRows.length) {
      return { planId, inserted: 0, reason: "No vehicle requirements in plan" };
    }

    const requiredCountByType = new Map<number, number>();
    for (const r of reqRows) {
      const vt = Number(r.vehicle_type_id ?? 0);
      const c = Math.max(0, Number(r.vehicle_count ?? 0));
      if (vt > 0 && c > 0) requiredCountByType.set(vt, (requiredCountByType.get(vt) ?? 0) + c);
    }

    const requiredVehicleTypeIds = Array.from(requiredCountByType.keys());
    if (!requiredVehicleTypeIds.length) {
      return { planId, inserted: 0, reason: "No positive vehicle counts" };
    }

    this.log("REQUIRED_SUMMARY", {
      planId,
      totalKmsNum,
      totalOutstationKmNum,
      totalTimeStr,
      noOfDays,
      required: Array.from(requiredCountByType.entries()),
      eligibleCities,
    });

    // ---------------------------------------------------------------------
    // 3) Clear existing vendor data for this plan
    // ---------------------------------------------------------------------
    const delEligibleWhere = { itinerary_plan_id: planId };
    this.logSql(
      "ELIGIBLE_DELETE_MANY",
      this.buildDeleteSql("dvi_itinerary_plan_vendor_eligible_list", delEligibleWhere),
      { where: delEligibleWhere },
    );
    const delEligibleRes =
      await tx.dvi_itinerary_plan_vendor_eligible_list.deleteMany({
        where: delEligibleWhere,
      });
    this.log("ELIGIBLE_DELETED", delEligibleRes);

    if (tAny?.dvi_itinerary_plan_vendor_vehicle_details) {
      const delDetailsWhere: any = { itinerary_plan_id: planId };
      this.logSql(
        "VENDOR_VEHICLE_DETAILS_DELETE_MANY",
        this.buildDeleteSql("dvi_itinerary_plan_vendor_vehicle_details", delDetailsWhere),
        { where: delDetailsWhere },
      );
      const delDetailsRes =
        await tAny.dvi_itinerary_plan_vendor_vehicle_details.deleteMany({
          where: delDetailsWhere,
        });
      this.log("VENDOR_VEHICLE_DETAILS_DELETED", delDetailsRes);
    }

    const kmsLimitCache = new Map<string, { kmsLimitId: number; allowedKmPerDayNum: number }>();
    const priceBookCache = new Map<string, number>();
    const existingQtyCache = new Map<string, number>();

    const vendorIdsUsed = new Set<number>();
    let inserted = 0;

    // ---------------------------------------------------------------------
    // MAIN ELIGIBLE-LIST BUILD (PHP-style vendor loop)
    // ---------------------------------------------------------------------
    for (const [planVehicleTypeId, requiredCount] of requiredCountByType.entries()) {
      if (!planVehicleTypeId || requiredCount <= 0) continue;

      let mappingsWhere: any = { vehicle_type_id: planVehicleTypeId, status: 1 };
      this.logSql(
        "VENDOR_VEHICLE_TYPES_FIND_MANY_1",
        this.buildSelectSql("dvi_vendor_vehicle_types", mappingsWhere),
        { where: mappingsWhere },
      );

      let mappings = await tx.dvi_vendor_vehicle_types.findMany({
        where: mappingsWhere,
        select: {
          vendor_vehicle_type_ID: true,
          vendor_id: true,
          vehicle_type_id: true,
        },
      });
      this.log("VENDOR_VEHICLE_TYPES_ROWS_1", mappings);

      if (!mappings.length) {
        mappingsWhere = { vendor_vehicle_type_ID: planVehicleTypeId, status: 1 };
        this.logSql(
          "VENDOR_VEHICLE_TYPES_FIND_MANY_2",
          this.buildSelectSql("dvi_vendor_vehicle_types", mappingsWhere),
          { where: mappingsWhere },
        );

        mappings = await tx.dvi_vendor_vehicle_types.findMany({
          where: mappingsWhere,
          select: {
            vendor_vehicle_type_ID: true,
            vendor_id: true,
            vehicle_type_id: true,
          },
        });
        this.log("VENDOR_VEHICLE_TYPES_ROWS_2", mappings);
      }

      if (!mappings.length) continue;

      for (const map of mappings) {
        const vendorVehicleTypeId = Number(map.vendor_vehicle_type_ID ?? 0);
        const vendorId = Number(map.vendor_id ?? 0);
        const masterVehicleTypeId = Number(map.vehicle_type_id ?? 0);
        if (!vendorVehicleTypeId || !vendorId) continue;

        this.log("VENDOR_VVT_PAIR", {
          planVehicleTypeId,
          vendorVehicleTypeId,
          vendorId,
          masterVehicleTypeId,
        });

        let allowedBranches: {
          vendor_branch_id: number;
          vendor_branch_name: string | null;
          vendor_branch_location: string | null;
        }[] = [];

        if (eligibleCityTokensLower.length) {
          const branchCityFilters = eligibleCityTokensLower.map((token) => ({
            vendor_branch_location: { contains: token },
          }));

          const branchWhere = {
            vendor_id: vendorId,
            status: 1,
            deleted: 0,
            OR: branchCityFilters,
          };
          this.logSql(
            "VENDOR_BRANCHES_FIND_MANY_FILTERED",
            this.buildSelectSql("dvi_vendor_branches", branchWhere),
            { where: branchWhere },
          );

          allowedBranches = await tx.dvi_vendor_branches.findMany({
            where: branchWhere,
            select: {
              vendor_branch_id: true,
              vendor_branch_name: true,
              vendor_branch_location: true,
            },
          });
          this.log("VENDOR_BRANCHES_FETCHED_FILTERED", allowedBranches);
        } else {
          const branchWhere = {
            vendor_id: vendorId,
            status: 1,
            deleted: 0,
          };
          this.logSql(
            "VENDOR_BRANCHES_FIND_MANY_NO_FILTER",
            this.buildSelectSql("dvi_vendor_branches", branchWhere),
            { where: branchWhere },
          );

          allowedBranches = await tx.dvi_vendor_branches.findMany({
            where: branchWhere,
            select: {
              vendor_branch_id: true,
              vendor_branch_name: true,
              vendor_branch_location: true,
            },
          });
          this.log("VENDOR_BRANCHES_FETCHED_NO_FILTER", allowedBranches);
        }

        const allowedBranchIds = allowedBranches
          .map((b) => Number(b.vendor_branch_id ?? 0))
          .filter((id) => id > 0);
        this.log("ALLOWED_BRANCH_IDS", { vendorId, allowedBranchIds });

        if (!allowedBranchIds.length) continue;

        const vehicleWhere: any = {
          vendor_id: vendorId,
          vendor_branch_id: { in: allowedBranchIds },
          status: 1,
          deleted: 0,
          OR: [
            { vehicle_type_id: vendorVehicleTypeId },
            ...(masterVehicleTypeId ? [{ vehicle_type_id: masterVehicleTypeId }] : []),
            { vehicle_type_id: planVehicleTypeId },
          ],
        };

        this.logSql(
          "VEHICLE_FIND_MANY",
          this.buildSelectSql("dvi_vehicle", vehicleWhere, "ORDER BY `vehicle_id` ASC"),
          { where: vehicleWhere },
        );

        const vehicles = await tx.dvi_vehicle.findMany({
          where: vehicleWhere,
          select: {
            vehicle_id: true,
            vendor_branch_id: true,
            extra_km_charge: true,
          },
          orderBy: { vehicle_id: "asc" },
        });
        this.log("VEHICLES_FETCHED", {
          vendorId,
          vendorVehicleTypeId,
          count: vehicles.length,
          vehicles,
        });

        if (!vehicles.length) continue;

        const branchNameById = new Map<number, string>(
          allowedBranches.map((b) => [
            Number(b.vendor_branch_id),
            String(b.vendor_branch_name ?? "").trim(),
          ]),
        );

        const maxToProcess = Math.min(vehicles.length, Math.max(requiredCount, 10));

        for (let idx = 0; idx < maxToProcess; idx++) {
          const vehicle = vehicles[idx];
          const vehicleId = Number(vehicle.vehicle_id ?? 0);
          const vendorBranchId = Number(vehicle.vendor_branch_id ?? 0);
          if (!vehicleId || !vendorBranchId) continue;

          const comboKey = `${vendorId}:${vendorBranchId}:${vendorVehicleTypeId}`;

          let currentQty = existingQtyCache.get(comboKey);
          if (currentQty === undefined) {
            const qtyWhere = {
              itinerary_plan_id: planId,
              vendor_id: vendorId,
              vendor_vehicle_type_id: vendorVehicleTypeId,
              vendor_branch_id: vendorBranchId,
              status: 1,
              deleted: 0,
            };
            this.logSql(
              "ELIGIBLE_QTY_CHECK_AGGREGATE",
              this.buildSelectSql(
                "dvi_itinerary_plan_vendor_eligible_list",
                qtyWhere,
              ),
              { where: qtyWhere, note: "aggregate + count in getPhpTotalVehicleQty" },
            );

            currentQty = await getPhpTotalVehicleQty(tx, qtyWhere);
            existingQtyCache.set(comboKey, currentQty);
            this.log("CURRENT_QTY_COMPUTED", { comboKey, currentQty });
          }

          const kmsKey = `${vendorId}:${vendorVehicleTypeId}`;
          let kms = kmsLimitCache.get(kmsKey);
          if (!kms) {
            const kmsWhere1 = {
              vendor_id: vendorId,
              vendor_vehicle_type_id: vendorVehicleTypeId,
              status: 1,
              deleted: 0,
            };
            this.logSql(
              "KMS_LIMIT_FIND_FIRST_1",
              this.buildSelectSql(
                "dvi_kms_limit",
                kmsWhere1,
                "ORDER BY `kms_limit_id` DESC LIMIT 1",
              ),
              { where: kmsWhere1 },
            );

            let kmsLimit = await tx.dvi_kms_limit.findFirst({
              where: kmsWhere1,
              select: { kms_limit_id: true, kms_limit: true },
              orderBy: { kms_limit_id: "desc" },
            });
            this.log("KMS_LIMIT_ROW_1", kmsLimit);

            if (!kmsLimit) {
              const kmsWhere2 = {
                vendor_id: vendorId,
                vendor_vehicle_type_id: vendorVehicleTypeId,
                status: 1,
              };
              this.logSql(
                "KMS_LIMIT_FIND_FIRST_2",
                this.buildSelectSql(
                  "dvi_kms_limit",
                  kmsWhere2,
                  "ORDER BY `kms_limit_id` DESC LIMIT 1",
                ),
                { where: kmsWhere2 },
              );

              kmsLimit = await tx.dvi_kms_limit.findFirst({
                where: kmsWhere2,
                select: { kms_limit_id: true, kms_limit: true },
                orderBy: { kms_limit_id: "desc" },
              });
              this.log("KMS_LIMIT_ROW_2", kmsLimit);
            }

            kms = {
              kmsLimitId: Number(kmsLimit?.kms_limit_id ?? 0),
              allowedKmPerDayNum: toNum(kmsLimit?.kms_limit),
            };
            kmsLimitCache.set(kmsKey, kms);
          }
          this.log("KMS_LIMIT_CACHE", { kmsKey, kms });

          const allowedKmPerDayNum = kms.allowedKmPerDayNum;
          const totalAllowedKmsNum =
            allowedKmPerDayNum > 0 ? allowedKmPerDayNum * noOfDays : 0;

          const extraKmRateNum = toNum(vehicle.extra_km_charge) || 0;
          const totalExtraKmsNum =
            totalAllowedKmsNum > 0 ? Math.max(0, totalOutstationKmNum - totalAllowedKmsNum) : 0;
          const totalExtraKmsChargeNum = totalExtraKmsNum * extraKmRateNum;

          const pbKey = `${vendorId}:${vendorBranchId}:${vendorVehicleTypeId}:${yearStr}:${monthStr}:${kms.kmsLimitId}`;
          let rentalPerDayNum = priceBookCache.get(pbKey);
          if (rentalPerDayNum === undefined) {
            rentalPerDayNum = 0;

            if (kms.kmsLimitId) {
              const pbWhere1 = {
                year: yearStr,
                month: monthStr,
                vendor_id: vendorId,
                vendor_branch_id: vendorBranchId,
                vehicle_type_id: vendorVehicleTypeId,
                kms_limit_id: kms.kmsLimitId,
                status: 1,
                deleted: 0,
              };
              this.logSql(
                "OUTSTATION_PRICE_BOOK_FIND_FIRST_1",
                this.buildSelectSql(
                  "dvi_vehicle_outstation_price_book",
                  pbWhere1,
                  "ORDER BY `id` DESC LIMIT 1",
                ),
                { where: pbWhere1 },
              );

              let pb = await tx.dvi_vehicle_outstation_price_book.findFirst({
                where: pbWhere1,
              });
              this.log("OUTSTATION_PRICE_BOOK_ROW_1", pb);

              if (!pb) {
                const pbWhere2 = {
                  year: yearStr,
                  month: monthStr,
                  vendor_id: vendorId,
                  vendor_branch_id: vendorBranchId,
                  vehicle_type_id: vendorVehicleTypeId,
                  kms_limit_id: kms.kmsLimitId,
                  status: 1,
                };
                this.logSql(
                  "OUTSTATION_PRICE_BOOK_FIND_FIRST_2",
                  this.buildSelectSql(
                    "dvi_vehicle_outstation_price_book",
                    pbWhere2,
                    "ORDER BY `id` DESC LIMIT 1",
                  ),
                  { where: pbWhere2 },
                );

                pb = await tx.dvi_vehicle_outstation_price_book.findFirst({
                  where: pbWhere2,
                });
                this.log("OUTSTATION_PRICE_BOOK_ROW_2", pb);
              }

              const col = `day_${dayOfMonth}`;
              rentalPerDayNum = toNum((pb as any)?.[col]);
              this.log("OUTSTATION_PRICE_BOOK_VALUE", {
                pbKey,
                col,
                rentalPerDayNum,
              });
            }

            priceBookCache.set(pbKey, rentalPerDayNum);
          }

          if (!rentalPerDayNum || rentalPerDayNum === 0) continue;

          const totalRentalNum = rentalPerDayNum * noOfDays;
          const vehicleGrandTotalNum = totalRentalNum + totalExtraKmsChargeNum;

          const vehicleOrigin = branchNameById.get(vendorBranchId) || "";

          const baseData: any = {
            itinerary_plan_id: planId,
            itineary_plan_assigned_status: 0,
            vehicle_type_id: planVehicleTypeId,
            vendor_id: vendorId,
            vendor_vehicle_type_id: vendorVehicleTypeId,
            total_vehicle_qty: 1,
            vehicle_count: 1,
            vehicle_id: vehicleId,
            vendor_branch_id: vendorBranchId,
            vehicle_orign: vehicleOrigin,
            outstation_allowed_km_per_day: String(allowedKmPerDayNum),
            total_kms: String(totalKmsNum),
            total_outstation_km: String(totalOutstationKmNum),
            total_time: String(totalTimeStr),
            total_rental_charges: totalRentalNum,
            extra_km_rate: String(extraKmRateNum),
            total_allowed_kms: String(totalAllowedKmsNum),
            total_extra_kms: String(totalExtraKmsNum),
            total_extra_kms_charge: totalExtraKmsChargeNum,
            vehicle_total_amount: vehicleGrandTotalNum,
            vehicle_grand_total: vehicleGrandTotalNum,
            createdby: createdBy,
            createdon: new Date(),
            updatedon: new Date(),
            status: 1,
            deleted: 0,
          };

          this.log("ELIGIBLE_ROW_BASEDATA", {
            comboKey,
            currentQty,
            requiredCount,
            baseData,
          });

          if (currentQty < requiredCount) {
            this.logSql(
              "ELIGIBLE_INSERT",
              this.buildInsertSql(
                "dvi_itinerary_plan_vendor_eligible_list",
                baseData,
              ),
              { data: baseData },
            );

            const createdEligible =
              await tx.dvi_itinerary_plan_vendor_eligible_list.create({
                data: baseData,
                select: { itinerary_plan_vendor_eligible_ID: true },
              });
            this.log("ELIGIBLE_CREATED", {
              planId,
              comboKey,
              createdEligible,
            });

            inserted++;
            vendorIdsUsed.add(vendorId);

            currentQty += 1;
            existingQtyCache.set(comboKey, currentQty);
            this.log("ELIGIBLE_QTY_AFTER_INSERT", {
              comboKey,
              currentQty,
            });
          } else {
            const updateWhere = {
              itinerary_plan_id: planId,
              vendor_vehicle_type_id: vendorVehicleTypeId,
              vehicle_id: vehicleId,
              vendor_branch_id: vendorBranchId,
            };

            const updateData = { ...baseData, createdon: undefined };
            this.logSql(
              "ELIGIBLE_UPDATE_MANY",
              this.buildUpdateSql(
                "dvi_itinerary_plan_vendor_eligible_list",
                updateData,
                updateWhere,
              ),
              { where: updateWhere, data: updateData },
            );

            const updRes =
              await tx.dvi_itinerary_plan_vendor_eligible_list.updateMany({
                where: updateWhere,
                data: updateData,
              });
            this.log("ELIGIBLE_UPDATED", { updateWhere, updRes });
          }
        }
      }
    }

    const vendorIdList = Array.from(vendorIdsUsed);
    this.log("VENDORS_USED", vendorIdList);

    if (vendorIdList.length > 0) {
      const delWhere = {
        itinerary_plan_id: planId,
        vendor_id: { notIn: vendorIdList },
      };
      this.logSql(
        "ELIGIBLE_DELETE_UNUSED_VENDORS",
        this.buildDeleteSql("dvi_itinerary_plan_vendor_eligible_list", delWhere),
        { where: delWhere },
      );
      const delRes =
        await tx.dvi_itinerary_plan_vendor_eligible_list.deleteMany({
          where: delWhere,
        });
      this.log("ELIGIBLE_UNUSED_VENDORS_DELETED", delRes);
    } else {
      const delAllWhere = { itinerary_plan_id: planId };
      this.logSql(
        "ELIGIBLE_DELETE_ALL_NO_VENDOR_MATCH",
        this.buildDeleteSql("dvi_itinerary_plan_vendor_eligible_list", delAllWhere),
        { where: delAllWhere },
      );
      const delAllRes =
        await tx.dvi_itinerary_plan_vendor_eligible_list.deleteMany({
          where: delAllWhere },
      );
      this.log("ELIGIBLE_ALL_DELETED", delAllRes);
    }

    const resetWhere = { itinerary_plan_id: planId };
    const resetData = { itineary_plan_assigned_status: 0 };
    this.logSql(
      "ELIGIBLE_RESET_ASSIGNED",
      this.buildUpdateSql(
        "dvi_itinerary_plan_vendor_eligible_list",
        resetData,
        resetWhere,
      ),
      { where: resetWhere, data: resetData },
    );
    const resetRes =
      await tx.dvi_itinerary_plan_vendor_eligible_list.updateMany({
        where: resetWhere,
        data: resetData,
      });
    this.log("ELIGIBLE_RESET_ASSIGNED_RESULT", resetRes);

    for (const [planVehicleTypeId, requiredCount] of requiredCountByType.entries()) {
      const picksWhere = {
        itinerary_plan_id: planId,
        vehicle_type_id: planVehicleTypeId,
        vehicle_grand_total: { gt: 0 },
        status: 1,
        deleted: 0,
      };
      this.logSql(
        "ELIGIBLE_SELECT_CHEAPEST",
        this.buildSelectSql(
          "dvi_itinerary_plan_vendor_eligible_list",
          picksWhere,
          `ORDER BY \`vehicle_grand_total\` ASC, \`itinerary_plan_vendor_eligible_ID\` ASC LIMIT ${Math.max(
            0,
            requiredCount,
          )}`,
        ),
        { where: picksWhere },
      );

      const picks =
        await tx.dvi_itinerary_plan_vendor_eligible_list.findMany({
          where: picksWhere,
          orderBy: [
            { vehicle_grand_total: "asc" },
            { itinerary_plan_vendor_eligible_ID: "asc" },
          ],
          take: Math.max(0, requiredCount),
          select: { itinerary_plan_vendor_eligible_ID: true },
        });
      this.log("ELIGIBLE_CHEAPEST_PICKS", {
        vehicle_type_id: planVehicleTypeId,
        requiredCount,
        picks,
      });

      const ids = picks
        .map((p: any) => Number(p.itinerary_plan_vendor_eligible_ID ?? 0))
        .filter((x: any) => x > 0);

      if (ids.length) {
        const markWhere = {
          itinerary_plan_vendor_eligible_ID: { in: ids },
        };
        const markData = { itineary_plan_assigned_status: 1 };
        this.logSql(
          "ELIGIBLE_MARK_ASSIGNED",
          this.buildUpdateSql(
            "dvi_itinerary_plan_vendor_eligible_list",
            markData,
            markWhere,
          ),
          { where: markWhere, data: markData },
        );

        const markRes =
          await tx.dvi_itinerary_plan_vendor_eligible_list.updateMany({
            where: markWhere,
            data: markData },
        );
        this.log("ELIGIBLE_MARK_ASSIGNED_RESULT", markRes);
      }
    }

    const cleanWhere = {
      itinerary_plan_id: planId,
      vehicle_type_id: { notIn: requiredVehicleTypeIds },
    };
    this.logSql(
      "ELIGIBLE_DELETE_UNUSED_TYPES",
      this.buildDeleteSql("dvi_itinerary_plan_vendor_eligible_list", cleanWhere),
      { where: cleanWhere },
    );
    const cleanRes =
      await tx.dvi_itinerary_plan_vendor_eligible_list.deleteMany({
        where: cleanWhere },
    );
    this.log("ELIGIBLE_UNUSED_TYPES_DELETED", cleanRes);

    // ---------------------------------------------------------------------
    // BUILD dvi_itinerary_plan_vendor_vehicle_details
    // EXACTLY FROM ASSIGNED ELIGIBLES (PHP parity)
    // ---------------------------------------------------------------------
    if (tAny?.dvi_itinerary_plan_vendor_vehicle_details && totalNoOfPlanRouteDetails > 0) {
      const delDetailsWhere2: any = { itinerary_plan_id: planId };
      this.logSql(
        "VENDOR_VEHICLE_DETAILS_DELETE_MANY_2",
        this.buildDeleteSql("dvi_itinerary_plan_vendor_vehicle_details", delDetailsWhere2),
        { where: delDetailsWhere2 },
      );
      const delDetRes2 =
        await tAny.dvi_itinerary_plan_vendor_vehicle_details.deleteMany({
          where: delDetailsWhere2 },
      );
      this.log("VENDOR_VEHICLE_DETAILS_DELETED_2", delDetRes2);

      const eligiblesWhere = {
        itinerary_plan_id: planId,
        itineary_plan_assigned_status: 1,
        status: 1,
        deleted: 0,
      };
      this.logSql(
        "ELIGIBLE_FIND_ALL_FOR_DETAILS",
        this.buildSelectSql(
          "dvi_itinerary_plan_vendor_eligible_list",
          eligiblesWhere,
        ),
        { where: eligiblesWhere },
      );

      const eligibles: any[] =
        await tx.dvi_itinerary_plan_vendor_eligible_list.findMany({
          where: eligiblesWhere },
      );
      this.log("ELIGIBLES_FOR_DETAILS", eligibles);

      const travelType = Number(plan.itinerary_type ?? 0) || 2; // 1=local, 2=outstation

      // keep helper call for logging / debugging (no hotspot override)
      const routeKmMap = await this.buildRouteKmMap(tx, planId, routes);
      this.log("ROUTE_KM_MAP_FOR_DETAILS", Object.fromEntries(routeKmMap));

      for (const e of eligibles) {
        const eligibleId = Number(e.itinerary_plan_vendor_eligible_ID ?? 0);
        if (!eligibleId) continue;

        const vehicleTypeId = Number(e.vehicle_type_id ?? 0);
        const vendorId = Number(e.vendor_id ?? 0);
        const vvtId = Number(e.vendor_vehicle_type_id ?? 0);
        const vehicleId = Number(e.vehicle_id ?? 0);
        const vendorBranchId = Number(e.vendor_branch_id ?? 0);
        const qty = Number(e.total_vehicle_qty ?? 1) || 1;

        const extraKmRateNum = toNum(e.extra_km_rate);
        const totalExtraKmsNum = toNum(e.total_extra_kms);
        const totalExtraKmsChargeNum = toNum(e.total_extra_kms_charge);
        const totalRentalNum = toNum(e.total_rental_charges);
        const vehicleGrandTotalNum = toNum(
          e.vehicle_grand_total || e.vehicle_total_amount,
        );

        this.log("DETAILS_ELIGIBLE_ROW", {
          eligibleId,
          vehicleTypeId,
          vendorId,
          vvtId,
          vehicleId,
          vendorBranchId,
          qty,
        });

        const planExtraKmNum = totalExtraKmsNum;
        // PHP: extra km duplicated per route
        const perRouteExtraKmNum = planExtraKmNum;

        for (const r of routes) {
          const routeId = Number(r.itinerary_route_ID ?? 0);
          if (!routeId) continue;

          const routeDate = safeDate(r.itinerary_route_date) || routeDateBase;

          // PHP: always route.no_of_km
          const runningKmNum = toNum(r.no_of_km);

          const pickupKmNum = 0;
          const dropKmNum = 0;

          const extraKmNum = perRouteExtraKmNum > 0 ? perRouteExtraKmNum : 0;

          const travelledKmNum =
            runningKmNum + pickupKmNum + dropKmNum + extraKmNum;

          const perRouteExtraKmChargeNum =
            extraKmRateNum > 0 ? extraKmNum * extraKmRateNum : 0;

          const fromLoc = (r.location_name ?? null) as any;
          const toLoc = (r.next_visiting_location ?? null) as any;

          const detailsData: any = {
            itinerary_plan_vendor_eligible_ID: eligibleId,
            itinerary_plan_id: planId,
            itinerary_route_id: routeId,
            itinerary_route_date: routeDate as any,
            vehicle_type_id: vehicleTypeId,
            vehicle_qty: qty,
            vendor_id: vendorId,
            vendor_vehicle_type_id: vvtId,
            vehicle_id: vehicleId,
            vendor_branch_id: vendorBranchId,
            time_limit_id: 0,
            kms_limit_id: 0,
            travel_type: travelType,
            itinerary_route_location_from: fromLoc,
            itinerary_route_location_to: toLoc,

            // --- DISTANCE FIELDS (strings, Prisma expects String/Null) ---
            total_running_km: runningKmNum.toFixed(2),      // string
            total_running_time: null,
            total_siteseeing_km: null,
            total_siteseeing_time: null,
            total_pickup_km: pickupKmNum.toFixed(2),        // string
            total_pickup_duration: null,
            total_drop_km: dropKmNum.toFixed(2),            // string
            total_drop_duration: null,
            total_extra_km: extraKmNum.toFixed(2),          // string
            extra_km_rate: extraKmRateNum,
            total_extra_km_charges: perRouteExtraKmChargeNum,
            total_travelled_km: travelledKmNum.toFixed(2),  // string
            total_travelled_time: null,

            // --- MONEY FIELDS (numbers) ---
            vehicle_rental_charges: totalRentalNum,
            vehicle_toll_charges: 0,
            vehicle_parking_charges: 0,
            vehicle_driver_charges: 0,
            vehicle_permit_charges: 0,
            before_6_am_extra_time: null,
            after_8_pm_extra_time: null,
            before_6_am_charges_for_driver: 0,
            before_6_am_charges_for_vehicle: 0,
            after_8_pm_charges_for_driver: 0,
            after_8_pm_charges_for_vehicle: 0,
            total_vehicle_amount: vehicleGrandTotalNum,

            createdby: createdBy,
            createdon: new Date(),
            updatedon: new Date(),
            status: 1,
            deleted: 0,
          };

          this.logSql(
            "VENDOR_VEHICLE_DETAILS_INSERT",
            this.buildInsertSql(
              "dvi_itinerary_plan_vendor_vehicle_details",
              detailsData,
            ),
            { data: detailsData },
          );
          this.log("VENDOR_VEHICLE_DETAILS_INSERT_DATA", detailsData);

          const createdDetails =
            await tAny.dvi_itinerary_plan_vendor_vehicle_details.create({
              data: detailsData,
            });
          this.log("VENDOR_VEHICLE_DETAILS_CREATED", {
            eligibleId,
            routeId,
            createdDetails,
          });
        }
      }
    }

    this.log("FINAL_RESULT", { planId, inserted });
    return { planId, inserted };
  }
}
