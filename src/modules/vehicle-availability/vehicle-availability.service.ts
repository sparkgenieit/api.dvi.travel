// FILE: src/modules/vehicle-availability/vehicle-availability.service.ts

import { Injectable } from '@nestjs/common';
import { PrismaService } from '../../prisma.service';
import { VehicleAvailabilityQueryDto } from './dto/vehicle-availability-query.dto';
import {
  VehicleAvailabilityResponseDto,
  VehicleAvailabilityRowDto,
  VehicleAvailabilityCellDto,
  VehicleAvailabilityRouteSegmentDto,
} from './dto/vehicle-availability-response.dto';

@Injectable()
export class VehicleAvailabilityService {
  constructor(private readonly prisma: PrismaService) {}

  // -------------------------------------------------------------------------
  // DATE HELPERS
  // -------------------------------------------------------------------------

  private pad(n: number): string {
    return n.toString().padStart(2, '0');
  }

  private toYmd(d: Date): string {
    return `${d.getFullYear()}-${this.pad(d.getMonth() + 1)}-${this.pad(
      d.getDate(),
    )}`;
  }

  private getDefaultMonthRange(): { dateFrom: string; dateTo: string } {
    const now = new Date();
    const first = new Date(now.getFullYear(), now.getMonth(), 1);
    const last = new Date(now.getFullYear(), now.getMonth() + 1, 0);

    const dateFrom = this.toYmd(first);
    const dateTo = this.toYmd(last);

    return { dateFrom, dateTo };
  }

  private buildDatesArray(dateFrom: string, dateTo: string): string[] {
    const dates: string[] = [];
    const start = new Date(`${dateFrom}T00:00:00.000Z`);
    const end = new Date(`${dateTo}T00:00:00.000Z`);

    for (
      let d = new Date(start.getTime());
      d.getTime() <= end.getTime();
      d.setUTCDate(d.getUTCDate() + 1)
    ) {
      dates.push(this.toYmd(d));
    }

    return dates;
  }

  private toDateRange(dateFrom: string, dateTo: string) {
    // inclusive [dateFrom, dateTo] in UTC
    const from = new Date(`${dateFrom}T00:00:00.000Z`);
    const to = new Date(`${dateTo}T23:59:59.999Z`);
    return { from, to };
  }

  // -------------------------------------------------------------------------
  // MAIN WORKFLOW – mirrors __ajax_vehicle_availability_list.php data side
  // -------------------------------------------------------------------------

  async getVehicleAvailabilityChart(
    query: VehicleAvailabilityQueryDto,
  ): Promise<VehicleAvailabilityResponseDto> {
    const { vendorId, vehicleTypeId } = query;

    // 1) DATE RANGE + dates[]
    const { dateFrom, dateTo } = (() => {
      if (query.dateFrom && query.dateTo) {
        return { dateFrom: query.dateFrom, dateTo: query.dateTo };
      }
      return this.getDefaultMonthRange();
    })();

    const dates = this.buildDatesArray(dateFrom, dateTo);
    const { from: rangeFrom, to: rangeTo } = this.toDateRange(
      dateFrom,
      dateTo,
    );
    const todayYmd = this.toYmd(new Date());

    // 2) BASE VEHICLES (VEHICLE in PHP)
    const vehicles = await this.prisma.dvi_vehicle.findMany({
      where: {
        status: 1,
        deleted: 0,
        ...(vendorId ? { vendor_id: vendorId } : {}),
        ...(vehicleTypeId ? { vehicle_type_id: vehicleTypeId } : {}),
      },
      orderBy: [
        { vendor_id: 'asc' },
        { vehicle_type_id: 'asc' },
        { vehicle_id: 'asc' },
      ],
    });

    if (vehicles.length === 0) {
      return { dates, rows: [] };
    }

    // 3) CONFIRMED VOUCHERS (CNF_ITINEARY_VEHICLE_VOUCER in PHP)
    const vouchers =
      await this.prisma.dvi_confirmed_itinerary_plan_vehicle_voucher_details.findMany(
        {
          where: {
            status: 1,
            deleted: 0,
            vehicle_booking_status: 4, // confirmed
            ...(vendorId ? { vendor_id: vendorId } : {}),
            ...(vehicleTypeId ? { vehicle_type_id: vehicleTypeId } : {}),
          },
        },
      );

    if (vouchers.length === 0) {
      return { dates, rows: [] };
    }

    const vouchersByItineraryId = new Map<number, any[]>();
    for (const v of vouchers as any[]) {
      const itId: number = v.itinerary_plan_id;
      if (!vouchersByItineraryId.has(itId)) {
        vouchersByItineraryId.set(itId, []);
      }
      vouchersByItineraryId.get(itId)!.push(v);
    }

    const itineraryIdsAll = Array.from(vouchersByItineraryId.keys());
    if (itineraryIdsAll.length === 0) {
      return { dates, rows: [] };
    }

    // 4) CONFIRMED ITINERARIES (CNF_ITINEARY in PHP)
    const itineraries =
      await this.prisma.dvi_confirmed_itinerary_plan_details.findMany({
        where: {
          status: 1,
          deleted: 0,
          itinerary_preference: { in: [2, 3] },
          itinerary_plan_ID: { in: itineraryIdsAll },
          OR: [
            {
              trip_start_date_and_time: {
                gte: rangeFrom,
                lte: rangeTo,
              },
            },
            {
              trip_end_date_and_time: {
                gte: rangeFrom,
                lte: rangeTo,
              },
            },
          ],
        },
        orderBy: { trip_start_date_and_time: 'asc' },
      });

    if (itineraries.length === 0) {
      return { dates, rows: [] };
    }

    const itineraryById = new Map<number, any>();
    for (const it of itineraries as any[]) {
      itineraryById.set(it.itinerary_plan_ID, it);
    }
    const filteredItineraryIds = Array.from(itineraryById.keys());

    // 5) ROUTE DETAILS (CNF_ROUTE in PHP)
    const routes =
      await this.prisma.dvi_confirmed_itinerary_route_details.findMany({
        where: {
          itinerary_plan_ID: { in: filteredItineraryIds },
          status: 1,
          deleted: 0,
        },
      });

    // group by (itinerary_plan_ID, itinerary_route_date)
    const routesByItineraryAndDate = new Map<string, any[]>();
    for (const r of routes as any[]) {
      const itId: number = r.itinerary_plan_ID;
      const dateObj =
        r.itinerary_route_date instanceof Date
          ? (r.itinerary_route_date as Date)
          : new Date(r.itinerary_route_date);
      const day = this.toYmd(dateObj);
      const key = `${itId}-${day}`;
      if (!routesByItineraryAndDate.has(key)) {
        routesByItineraryAndDate.set(key, []);
      }
      routesByItineraryAndDate.get(key)!.push(r);
    }

    // 6) VEHICLE ASSIGNMENTS (dvi_confirmed_itinerary_vendor_vehicle_assigned)
    const vehicleAssignments =
      await this.prisma.dvi_confirmed_itinerary_vendor_vehicle_assigned.findMany(
        {
          where: {
            itinerary_plan_id: { in: filteredItineraryIds },
            status: 1,
            deleted: 0,
          },
        },
      );

    // keyed by (itinerary, vendor, vehicle_type)
    const assignedVehicleIdsByItineraryVendorType = new Map<string, number[]>();
    // keyed by (itinerary, vehicle_id)
    const assignmentByItineraryAndVehicle = new Map<string, any>();

    for (const a of vehicleAssignments as any[]) {
      const itId: number = a.itinerary_plan_id;
      const vId: number = a.vendor_id;
      const vtId: number = a.vehicle_type_id;
      const vehicleId: number = a.vehicle_id;

      const keyVendorType = `${itId}-${vId}-${vtId}`;
      if (!assignedVehicleIdsByItineraryVendorType.has(keyVendorType)) {
        assignedVehicleIdsByItineraryVendorType.set(keyVendorType, []);
      }
      assignedVehicleIdsByItineraryVendorType
        .get(keyVendorType)!
        .push(vehicleId);

      const keyVehicle = `${itId}-${vehicleId}`;
      assignmentByItineraryAndVehicle.set(keyVehicle, a);
    }

    // 7) DRIVER ASSIGNMENTS (dvi_confirmed_itinerary_vendor_driver_assigned)
    const driverAssignments =
      await this.prisma.dvi_confirmed_itinerary_vendor_driver_assigned.findMany(
        {
          where: {
            itinerary_plan_id: { in: filteredItineraryIds },
            status: 1,
            deleted: 0,
          },
        },
      );

    const driverByItineraryAndVehicle = new Map<string, any>();
    for (const d of driverAssignments as any[]) {
      const itId: number = d.itinerary_plan_id;
      const vehicleId: number = d.vehicle_id;
      const key = `${itId}-${vehicleId}`;
      driverByItineraryAndVehicle.set(key, d);
    }

    // 8) BUILD MAP: (vendor_id, vehicle_type_id) -> ordered itinerary ids
    const itineraryIdsByVendorType = new Map<string, number[]>();

    for (const it of itineraries as any[]) {
      const itId = it.itinerary_plan_ID;
      const attachedVouchers = vouchersByItineraryId.get(itId) || [];

      for (const v of attachedVouchers as any[]) {
        const key = `${v.vendor_id}-${v.vehicle_type_id}`;

        if (!itineraryIdsByVendorType.has(key)) {
          itineraryIdsByVendorType.set(key, []);
        }
        itineraryIdsByVendorType.get(key)!.push(itId);
      }
    }

    // dedupe + sort by trip_start_date
    for (const [key, ids] of itineraryIdsByVendorType.entries()) {
      const uniqueIds = Array.from(new Set(ids));
      uniqueIds.sort((a, b) => {
        const ia = itineraryById.get(a);
        const ib = itineraryById.get(b);
        const ta = ia?.trip_start_date_and_time
          ? new Date(ia.trip_start_date_and_time).getTime()
          : 0;
        const tb = ib?.trip_start_date_and_time
          ? new Date(ib.trip_start_date_and_time).getTime()
          : 0;
        return ta - tb;
      });
      itineraryIdsByVendorType.set(key, uniqueIds);
    }

    // 9) VENDOR + VEHICLE TYPE LABELS (getVENDOR_DETAILS, getVENDOR_VEHICLE_TYPES in PHP)
    const vendorIds = Array.from(
      new Set(vehicles.map((v: any) => v.vendor_id as number)),
    );
    const vehicleTypeIds = Array.from(
      new Set(vehicles.map((v: any) => v.vehicle_type_id as number)),
    );

    const [vendorRows, vehicleTypeRows] = await Promise.all([
      this.prisma.dvi_vendor_details.findMany({
        where: { vendor_id: { in: vendorIds } },
        select: { vendor_id: true, vendor_name: true },
      }),
      this.prisma.dvi_vehicle_type.findMany({
        where: { vehicle_type_id: { in: vehicleTypeIds } },
        select: { vehicle_type_id: true, vehicle_type_title: true },
      }),
    ]);

    const vendorNameById = new Map<number, string>();
    for (const v of vendorRows as any[]) {
      vendorNameById.set(v.vendor_id, v.vendor_name ?? '');
    }

    const vehicleTypeTitleById = new Map<number, string>();
    for (const vt of vehicleTypeRows as any[]) {
      vehicleTypeTitleById.set(vt.vehicle_type_id, vt.vehicle_type_title ?? '');
    }

    // 10) BUILD FINAL ROWS (one per vehicle, like PHP HTML table)
    const rows: VehicleAvailabilityRowDto[] = [];

    for (const vehicle of vehicles as any[]) {
      const vendorIdKey: number = vehicle.vendor_id;
      const vehicleTypeIdKey: number = vehicle.vehicle_type_id;
      const vehicleIdKey: number = vehicle.vehicle_id;

      const vendorTypeKey = `${vendorIdKey}-${vehicleTypeIdKey}`;
      const itineraryIdsForType =
        itineraryIdsByVendorType.get(vendorTypeKey) || [];

      if (itineraryIdsForType.length === 0) {
        // Equivalent to HAVING confirmed_itinerary_count > 0 in PHP
        continue;
      }

      const vendorName = vendorNameById.get(vendorIdKey) ?? '';
      const vehicleTypeTitle =
        vehicleTypeTitleById.get(vehicleTypeIdKey) ?? '';

      const cells: VehicleAvailabilityCellDto[] = [];

      for (const day of dates) {
        let cell: VehicleAvailabilityCellDto | null = null;

        // find first itinerary that "covers" this date for this vendor+vehicle_type
        for (const itId of itineraryIdsForType) {
          const it = itineraryById.get(itId);
          if (!it) continue;

          const startDate =
            it.trip_start_date_and_time instanceof Date
              ? (it.trip_start_date_and_time as Date)
              : new Date(it.trip_start_date_and_time);
          const endDate =
            it.trip_end_date_and_time instanceof Date
              ? (it.trip_end_date_and_time as Date)
              : new Date(it.trip_end_date_and_time);

          const startYmd = this.toYmd(startDate);
          const endYmd = this.toYmd(endDate);

          if (day < startYmd || day > endYmd) {
            continue; // this itinerary does not occupy this day
          }

          // does this itinerary have vehicle assignments for this vendor+type?
          const assignedForVendorType =
            assignedVehicleIdsByItineraryVendorType.get(
              `${itId}-${vendorIdKey}-${vehicleTypeIdKey}`,
            ) || [];

          const isVehicleAssigned =
            assignedForVendorType.length > 0 &&
            assignedForVendorType.includes(vehicleIdKey);

          // rule (approx PHP):
          // - if there are assignments for this itinerary+vendor+type,
          //   show it only on the assigned vehicle rows
          // - if there are NO assignments yet, treat as "unassigned" and
          //   still expose it so UI can show "Assign Vehicle"
          if (assignedForVendorType.length > 0 && !isVehicleAssigned) {
            continue;
          }

          const isStart = day === startYmd;
          const isEnd = day === endYmd;
          const isInBetween = !isStart && !isEnd;
          const isWithinTrip = true;
          const isToday = day === todayYmd;

          const keyRoute = `${itId}-${day}`;
          const routeRows = routesByItineraryAndDate.get(keyRoute) || [];
          const routeSegments: VehicleAvailabilityRouteSegmentDto[] =
            routeRows.map((r: any) => ({
              locationName: r.location_name ?? '',
              nextVisitingLocation: r.next_visiting_location ?? '',
            }));

          const assignmentKey = `${itId}-${vehicleIdKey}`;
          const driverAssignment = driverByItineraryAndVehicle.get(
            assignmentKey,
          );
          const hasDriver = !!driverAssignment;
          const driverId = hasDriver ? (driverAssignment.driver_id as number) : null;

          cell = {
            date: day,
            itineraryPlanId: itId,
            itineraryQuoteId: it.itinerary_quote_ID ?? null,
            isWithinTrip,
            isStart,
            isEnd,
            isInBetween,
            isToday,
            isVehicleAssigned,
            assignedVehicleId: isVehicleAssigned ? vehicleIdKey : null,
            hasDriver,
            driverId,
            routeSegments,
          };

          // We stop at first matching itinerary per day/vehicle,
          // which matches how the PHP table shows one block per cell.
          break;
        }

        if (!cell) {
          // no itinerary on this date for this vehicle
          cell = {
            date: day,
            itineraryPlanId: null,
            itineraryQuoteId: null,
            isWithinTrip: false,
            isStart: false,
            isEnd: false,
            isInBetween: false,
            isToday: day === todayYmd,
            isVehicleAssigned: false,
            assignedVehicleId: null,
            hasDriver: false,
            driverId: null,
            routeSegments: [],
          };
        }

        cells.push(cell);
      }

      rows.push({
        vendorId: vendorIdKey,
        vendorName,
        vehicleTypeId: vehicleTypeIdKey,
        vehicleTypeTitle,
        vehicleId: vehicleIdKey,
        registrationNumber: String(vehicle.registration_number),
        cells,
      });
    }

    return { dates, rows };
  }

  // -------------------------------------------------------------------------
  // LOOKUP HELPERS (for dropdowns – mirrors PHP side filters)
  // -------------------------------------------------------------------------

  /**
   * Vehicle types lookup (for filters / dropdowns).
   * Includes both deleted = 0 and deleted = 1.
   */
  async listVehicleTypes() {
    const rows = await this.prisma.dvi_vehicle_type.findMany({
      where: {
        status: 1,
        deleted: { in: [0, 1] },
      },
      select: {
        vehicle_type_id: true,
        vehicle_type_title: true,
      },
      orderBy: {
        vehicle_type_id: 'asc',
      },
    });

    return rows.map((r: any) => ({
      id: r.vehicle_type_id,
      label: r.vehicle_type_title ?? '',
    }));
  }

  /**
   * Vendors lookup (for filters / dropdowns).
   * Includes both deleted = 0 and deleted = 1.
   */
  async listVendors() {
    const vendors = await this.prisma.dvi_vendor_details.findMany({
      where: {
        deleted: { in: [0, 1] },
      },
      select: {
        vendor_id: true,
        vendor_name: true,
      },
      orderBy: {
        vendor_id: 'asc',
      },
    });

    return vendors.map((v: any) => ({
      id: v.vendor_id,
      label: v.vendor_name ?? '',
    }));
  }

  /**
   * Agents lookup (for filters / dropdowns).
   * Includes both deleted = 0 and 1.
   */
  async listAgents() {
    const agents = await this.prisma.dvi_agent.findMany({
      where: {
        deleted: { in: [0, 1] },
      },
      select: {
        agent_ID: true,
        agent_name: true,
      },
      orderBy: {
        agent_name: 'asc',
      },
    });

    return agents.map((a: any) => ({
      id: a.agent_ID,
      label: a.agent_name ?? '',
    }));
  }
}
