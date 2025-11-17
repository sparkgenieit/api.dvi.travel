import { Injectable } from "@nestjs/common";
import { PrismaService } from "../../prisma.service";
import {
  AccountsManagerQueryDto,
  AccountsManagerStatus,
  AccountsManagerComponentType,
} from "./dto/accounts-manager-query.dto";
import {
  AccountsManagerRowDto,
  AccountsManagerRowComponentType,
} from "./dto/accounts-manager-row.dto";

@Injectable()
export class AccountsManagerService {
  constructor(private readonly prisma: PrismaService) {}

  async list(query: AccountsManagerQueryDto): Promise<AccountsManagerRowDto[]> {
    const status: AccountsManagerStatus = query.status || "all";
    const componentType: AccountsManagerComponentType =
      query.componentType || "all";

    const fromDate = parseDDMMYYYY(query.fromDate);
    const toDate = parseDDMMYYYY(query.toDate, true); // end of day

    // 1️⃣ Base filter on accounts header
    const detailsWhere: any = {
      deleted: 0,
    };

    if (query.quoteId) {
      detailsWhere.itinerary_quote_ID = {
        contains: query.quoteId,
      };
    }

    if (fromDate && toDate) {
      detailsWhere.trip_start_date_and_time = { gte: fromDate };
      detailsWhere.trip_end_date_and_time = { lte: toDate };
    } else if (fromDate) {
      detailsWhere.trip_start_date_and_time = { gte: fromDate };
    } else if (toDate) {
      detailsWhere.trip_end_date_and_time = { lte: toDate };
    }

    const headers = await this.prisma.dvi_accounts_itinerary_details.findMany({
      where: detailsWhere,
      select: {
        accounts_itinerary_details_ID: true,
        itinerary_quote_ID: true,
        agent_id: true,
        trip_start_date_and_time: true,
        trip_end_date_and_time: true,
      },
    });

    if (!headers.length) {
      return [];
    }

    const headersById = new Map<
      number,
      (typeof headers)[number]
    >();
    const headerIds: number[] = [];

    for (const h of headers) {
      headersById.set(h.accounts_itinerary_details_ID, h);
      headerIds.push(h.accounts_itinerary_details_ID);
    }

    // 2️⃣ Agents map (for agent name filter + display)
    const agentIds = Array.from(
      new Set(headers.map((h) => h.agent_id).filter((x) => x && x > 0)),
    );

    const agents = agentIds.length
      ? await this.prisma.dvi_agent.findMany({
          where: {
            agent_ID: { in: agentIds },
          },
          select: {
            agent_ID: true,
            agent_name: true,
          },
        })
      : [];

    const agentMap = new Map<number, string>();
    for (const a of agents) {
      agentMap.set(a.agent_ID, a.agent_name || "");
    }

    // If agent name filter is provided, restrict headers here
    let filteredHeaderIds = headerIds;
    if (query.agent) {
      const needle = query.agent.toLowerCase();
      filteredHeaderIds = headerIds.filter((id) => {
        const header = headersById.get(id);
        if (!header) return false;
        const aName = (agentMap.get(header.agent_id) || "").toLowerCase();
        return aName.includes(needle);
      });
    }

    if (!filteredHeaderIds.length) {
      return [];
    }

    const shouldIncludeByStatus = (balance: number): boolean => {
      const rowStatus: "paid" | "due" = balance === 0 ? "paid" : "due";
      if (status === "all") return true;
      return status === rowStatus;
    };

    const rows: AccountsManagerRowDto[] = [];

    // Shared helper to build base row from header
    const buildBaseRow = (
      detailId: number,
      vendorName: string,
      amount: number,
      paid: number,
      balance: number,
      component: AccountsManagerRowComponentType,
    ): AccountsManagerRowDto | null => {
      if (!shouldIncludeByStatus(balance)) return null;

      const header = headersById.get(detailId);
      if (!header) return null;

      const quoteId = header.itinerary_quote_ID || "";
      const agentName = agentMap.get(header.agent_id) || "";

      // search filter (quote + vendor name)
      if (query.search) {
        const s = query.search.toLowerCase();
        if (
          !quoteId.toLowerCase().includes(s) &&
          !vendorName.toLowerCase().includes(s)
        ) {
          return null;
        }
      }

      const rowStatus: "paid" | "due" = balance === 0 ? "paid" : "due";

      return {
        id: detailId, // will be overwritten per component with detail row id
        quoteId,
        hotelName: vendorName,
        amount,
        payout: paid,
        payable: balance,
        status: rowStatus,
        componentType: component,
        agent: agentName,
        startDate: formatToDDMMYYYY(header.trip_start_date_and_time),
        endDate: formatToDDMMYYYY(header.trip_end_date_and_time),
      };
    };

    // 3️⃣ HOTEL component
    if (componentType === "all" || componentType === "hotel") {
      const hotelDetails =
        await this.prisma.dvi_accounts_itinerary_hotel_details.findMany({
          where: {
            deleted: 0,
            accounts_itinerary_details_ID: { in: filteredHeaderIds },
          },
          select: {
            accounts_itinerary_hotel_details_ID: true,
            accounts_itinerary_details_ID: true,
            hotel_id: true,
            total_payable: true,
            total_paid: true,
            total_balance: true,
          },
        });

      const hotelIds = Array.from(
        new Set(hotelDetails.map((h) => h.hotel_id).filter((x) => x && x > 0)),
      );

      const hotels = hotelIds.length
        ? await this.prisma.dvi_hotel.findMany({
            where: { hotel_id: { in: hotelIds } },
            select: { hotel_id: true, hotel_name: true },
          })
        : [];

      const hotelMap = new Map<number, string>();
      for (const h of hotels) {
        hotelMap.set(h.hotel_id, h.hotel_name || "");
      }

      for (const hd of hotelDetails) {
        const vendorName = hotelMap.get(hd.hotel_id) || "Hotel";
        const base = buildBaseRow(
          hd.accounts_itinerary_details_ID,
          vendorName,
          hd.total_payable,
          hd.total_paid,
          hd.total_balance,
          "hotel",
        );
        if (!base) continue;
        base.id = hd.accounts_itinerary_hotel_details_ID;
        rows.push(base);
      }
    }

    // 4️⃣ GUIDE component
    if (componentType === "all" || componentType === "guide") {
      const guideDetails =
        await this.prisma.dvi_accounts_itinerary_guide_details.findMany({
          where: {
            deleted: 0,
            accounts_itinerary_details_ID: { in: filteredHeaderIds },
          },
          select: {
            accounts_itinerary_guide_details_ID: true,
            accounts_itinerary_details_ID: true,
            guide_id: true,
            total_payable: true,
            total_paid: true,
            total_balance: true,
          },
        });

      const guideIds = Array.from(
        new Set(
          guideDetails.map((g) => g.guide_id).filter((x) => x && x > 0),
        ),
      );

      const guides = guideIds.length
        ? await this.prisma.dvi_guide_details.findMany({
            where: { guide_id: { in: guideIds } },
            select: { guide_id: true, guide_name: true },
          })
        : [];

      const guideMap = new Map<number, string>();
      for (const g of guides) {
        guideMap.set(g.guide_id, g.guide_name || "");
      }

      for (const gd of guideDetails) {
        const vendorName = guideMap.get(gd.guide_id) || "Guide";
        const base = buildBaseRow(
          gd.accounts_itinerary_details_ID,
          vendorName,
          gd.total_payable,
          gd.total_paid,
          gd.total_balance,
          "guide",
        );
        if (!base) continue;
        base.id = gd.accounts_itinerary_guide_details_ID;
        rows.push(base);
      }
    }

    // 5️⃣ HOTSPOT component
    if (componentType === "all" || componentType === "hotspot") {
      const hotspotDetails =
        await this.prisma.dvi_accounts_itinerary_hotspot_details.findMany({
          where: {
            deleted: 0,
            accounts_itinerary_details_ID: { in: filteredHeaderIds },
          },
          select: {
            accounts_itinerary_hotspot_details_ID: true,
            accounts_itinerary_details_ID: true,
            hotspot_ID: true,
            total_payable: true,
            total_paid: true,
            total_balance: true,
          },
        });

      const hotspotIds = Array.from(
        new Set(
          hotspotDetails.map((h) => h.hotspot_ID).filter((x) => x && x > 0),
        ),
      );

      const hotspots = hotspotIds.length
        ? await this.prisma.dvi_hotspot_place.findMany({
            where: { hotspot_ID: { in: hotspotIds } },
            select: { hotspot_ID: true, hotspot_name: true },
          })
        : [];

      const hotspotMap = new Map<number, string>();
      for (const h of hotspots) {
        hotspotMap.set(h.hotspot_ID, h.hotspot_name || "");
      }

      for (const hd of hotspotDetails) {
        const vendorName = hotspotMap.get(hd.hotspot_ID) || "Hotspot";
        const base = buildBaseRow(
          hd.accounts_itinerary_details_ID,
          vendorName,
          hd.hotspot_amount ?? hd.total_payable, // if hotspot_amount exists, use it; else total_payable
          hd.total_paid,
          hd.total_balance,
          "hotspot",
        );
        if (!base) continue;
        base.id = hd.accounts_itinerary_hotspot_details_ID;
        rows.push(base);
      }
    }

    // 6️⃣ ACTIVITY component
    if (componentType === "all" || componentType === "activity") {
      const activityDetails =
        await this.prisma.dvi_accounts_itinerary_activity_details.findMany({
          where: {
            deleted: 0,
            accounts_itinerary_details_ID: { in: filteredHeaderIds },
          },
          select: {
            accounts_itinerary_activity_details_ID: true,
            accounts_itinerary_details_ID: true,
            activity_ID: true,
            activity_amount: true,
            total_payable: true,
            total_paid: true,
            total_balance: true,
          },
        });

      const activityIds = Array.from(
        new Set(
          activityDetails
            .map((a) => a.activity_ID)
            .filter((x) => x && x > 0),
        ),
      );

      const activities = activityIds.length
        ? await this.prisma.dvi_activity.findMany({
            where: { activity_id: { in: activityIds } },
            select: { activity_id: true, activity_title: true },
          })
        : [];

      const activityMap = new Map<number, string>();
      for (const a of activities) {
        activityMap.set(a.activity_id, a.activity_title || "");
      }

      for (const ad of activityDetails) {
        const vendorName = activityMap.get(ad.activity_ID) || "Activity";
        const amount = ad.activity_amount ?? ad.total_payable;
        const base = buildBaseRow(
          ad.accounts_itinerary_details_ID,
          vendorName,
          amount,
          ad.total_paid,
          ad.total_balance,
          "activity",
        );
        if (!base) continue;
        base.id = ad.accounts_itinerary_activity_details_ID;
        rows.push(base);
      }
    }

    // 7️⃣ VEHICLE component
    if (componentType === "all" || componentType === "vehicle") {
      const vehicleDetails =
        await this.prisma.dvi_accounts_itinerary_vehicle_details.findMany({
          where: {
            deleted: 0,
            accounts_itinerary_details_ID: { in: filteredHeaderIds },
          },
          select: {
            accounts_itinerary_vehicle_details_ID: true,
            accounts_itinerary_details_ID: true,
            vehicle_id: true,
            total_payable: true,
            total_paid: true,
            total_balance: true,
          },
        });

      const vehicleIds = Array.from(
        new Set(
          vehicleDetails.map((v) => v.vehicle_id).filter((x) => x && x > 0),
        ),
      );

      const vehicles = vehicleIds.length
        ? await this.prisma.dvi_vehicle.findMany({
            where: { vehicle_id: { in: vehicleIds } },
            select: {
              vehicle_id: true,
              registration_number: true,
              owner_name: true,
            },
          })
        : [];

      const vehicleMap = new Map<number, string>();
      for (const v of vehicles) {
        const label =
          v.registration_number ||
          v.owner_name ||
          `Vehicle #${v.vehicle_id}`;
        vehicleMap.set(v.vehicle_id, label);
      }

      for (const vd of vehicleDetails) {
        const vendorName = vehicleMap.get(vd.vehicle_id) || "Vehicle";
        const base = buildBaseRow(
          vd.accounts_itinerary_details_ID,
          vendorName,
          vd.total_payable,
          vd.total_paid,
          vd.total_balance,
          "vehicle",
        );
        if (!base) continue;
        base.id = vd.accounts_itinerary_vehicle_details_ID;
        rows.push(base);
      }
    }

    // 8️⃣ Sort by start date desc + quoteId as fallback
    rows.sort((a, b) => {
      const da = toComparable(a.startDate);
      const db = toComparable(b.startDate);
      if (da === db) {
        return (b.quoteId || "").localeCompare(a.quoteId || "");
      }
      return db.localeCompare(da);
    });

    return rows;
  }
}

// Helpers (pure functions)

function parseDDMMYYYY(
  value?: string,
  endOfDay = false,
): Date | undefined {
  if (!value) return undefined;
  const parts = value.split("/");
  if (parts.length !== 3) return undefined;
  const [dd, mm, yyyy] = parts;
  const d = parseInt(dd, 10);
  const m = parseInt(mm, 10);
  const y = parseInt(yyyy, 10);
  if (!d || !m || !y) return undefined;

  if (endOfDay) {
    return new Date(y, m - 1, d, 23, 59, 59, 999);
  }
  return new Date(y, m - 1, d, 0, 0, 0, 0);
}

function formatToDDMMYYYY(
  date?: Date | string | null,
): string {
  if (!date) return "";
  const dObj = typeof date === "string" ? new Date(date) : date;
  if (Number.isNaN(dObj.getTime())) return "";
  const dd = `${dObj.getDate()}`.padStart(2, "0");
  const mm = `${dObj.getMonth() + 1}`.padStart(2, "0");
  const yyyy = dObj.getFullYear();
  return `${dd}/${mm}/${yyyy}`;
}

// for sorting: DD/MM/YYYY -> YYYYMMDD
function toComparable(ddmmyyyy?: string): string {
  if (!ddmmyyyy) return "";
  const [d, m, y] = ddmmyyyy.split("/");
  return `${y || "0000"}${m || "00"}${d || "00"}`;
}
