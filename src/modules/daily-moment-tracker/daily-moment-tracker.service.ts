// FILE: src/modules/daily-moment-tracker/daily-moment-tracker.service.ts

import { Injectable, BadRequestException } from '@nestjs/common';
import { PrismaService } from '../../prisma.service';
import {
  DailyMomentRowDto,
  DailyMomentChargeRowDto,
  DriverRatingRowDto,
  GuideRatingRowDto,
  ListDailyMomentQueryDto,
  UpsertDailyMomentChargeDto,
} from './dto/daily-moment-tracker.dto';

@Injectable()
export class DailyMomentTrackerService {
  constructor(private readonly prisma: PrismaService) {}

  // ========= PUBLIC API METHODS =========

  async listDailyMoments(
    query: ListDailyMomentQueryDto,
  ): Promise<DailyMomentRowDto[]> {
    const from = this.parseDate(query.fromDate);
    const to = this.parseDate(query.toDate);

    if (from > to) {
      throw new BadRequestException('fromDate cannot be after toDate');
    }

    // 1) Base rows: confirmed plan + confirmed route (status=1, deleted=0, date range)
    const routes =
      await this.prisma.dvi_confirmed_itinerary_route_details.findMany({
        where: {
          deleted: 0,
          status: 1,
          itinerary_route_date: {
            gte: from,
            lte: to,
          },
        },
        orderBy: {
          itinerary_route_date: 'asc',
        },
      });

    if (!routes.length) {
      return [];
    }

    const planIds = Array.from(
      new Set(routes.map((r) => r.itinerary_plan_ID)),
    );
    const plans =
      await this.prisma.dvi_confirmed_itinerary_plan_details.findMany({
        where: {
          itinerary_plan_ID: { in: planIds },
          deleted: 0,
          status: 1,
          ...(query.itineraryPlanId
            ? { itinerary_plan_ID: query.itineraryPlanId }
            : {}),
          ...(query.agentId ? { agent_id: query.agentId } : {}),
        },
      });

    if (!plans.length) {
      return [];
    }

    const planByPlanId = new Map<number, (typeof plans)[number]>();
    plans.forEach((p) => planByPlanId.set(p.itinerary_plan_ID, p));

    // Filter routes to ones whose plan passes agent/plan filters
    const filteredRoutes = routes.filter((r) =>
      planByPlanId.has(r.itinerary_plan_ID),
    );
    if (!filteredRoutes.length) {
      return [];
    }

    // 2) Prefetch related tables in batches to avoid N+1

    const effectivePlanIds = Array.from(planByPlanId.keys());
    const routeIds = Array.from(
      new Set(filteredRoutes.map((r) => r.itinerary_route_ID)),
    );

    // 2.1 Primary customer per plan
    const customers =
      await this.prisma.dvi_confirmed_itinerary_customer_details.findMany({
        where: {
          itinerary_plan_ID: { in: effectivePlanIds },
          primary_customer: 1,
          deleted: 0,
          status: 1,
        },
      });
    const primaryCustomerByPlan = new Map<
      number,
      (typeof customers)[number]
    >();
    customers.forEach((c) =>
      primaryCustomerByPlan.set(c.itinerary_plan_ID, c),
    );

    // 2.2 Hotel + meal flags per plan+route from confirmed hotel room details
    const hotelRoomDetails =
      await this.prisma.dvi_confirmed_itinerary_plan_hotel_room_details.findMany(
        {
          where: {
            itinerary_plan_id: { in: effectivePlanIds },
            itinerary_route_id: { in: routeIds },
            deleted: 0,
            status: 1,
          },
        },
      );

    type HotelKey = string; // `${planId}:${routeId}`
    const hotelRoomByPlanRoute = new Map<
      HotelKey,
      (typeof hotelRoomDetails)[number][]
    >();
    const hotelIds = new Set<number>();
    hotelRoomDetails.forEach((hr) => {
      const key = this.mkPlanRouteKey(
        hr.itinerary_plan_id,
        hr.itinerary_route_id,
      );
      const arr = hotelRoomByPlanRoute.get(key) ?? [];
      arr.push(hr);
      hotelRoomByPlanRoute.set(key, arr);
      if (hr.hotel_id) {
        hotelIds.add(hr.hotel_id);
      }
    });

    const hotels = hotelIds.size
      ? await this.prisma.dvi_hotel.findMany({
          where: {
            hotel_id: { in: Array.from(hotelIds) },
            deleted: false,
            status: 1,
          },
          select: {
            hotel_id: true,
            hotel_name: true,
            // IMPORTANT: we do NOT select `updatedon` (or any other datetime)
          },
        })
      : [];
    const hotelById = new Map<number, (typeof hotels)[number]>();
    hotels.forEach((h) => hotelById.set(h.hotel_id, h));

    // 2.3 Vendor + vehicle per plan+route
    const planVendorVehicle =
      await this.prisma.dvi_confirmed_itinerary_plan_vendor_vehicle_details.findMany(
        {
          where: {
            itinerary_plan_id: { in: effectivePlanIds },
            itinerary_route_id: { in: routeIds },
            deleted: 0,
            status: 1,
          },
          select: {
            itinerary_plan_id: true,
            itinerary_route_id: true,
            vehicle_type_id: true,
            vendor_id: true,
            vehicle_id: true,
            vendor_branch_id: true,
            // NOTE: we intentionally do NOT select total_*_time or *_duration
          },
        },
      );

    const vendorIds = new Set<number>();
    const vehicleTypeIds = new Set<number>();
    const vehicleIds = new Set<number>();

    type VendorVehKey = string; // `${planId}:${routeId}`
    const vendorVehByPlanRoute = new Map<
      VendorVehKey,
      (typeof planVendorVehicle)[number]
    >();

    planVendorVehicle.forEach((pv) => {
      const key = this.mkPlanRouteKey(
        pv.itinerary_plan_id,
        pv.itinerary_route_id,
      );
      // If multiple rows exist, we keep the first one (similar to PHP helpers)
      if (!vendorVehByPlanRoute.has(key)) {
        vendorVehByPlanRoute.set(key, pv);
      }
      if (pv.vendor_id) vendorIds.add(pv.vendor_id);
      if (pv.vehicle_type_id) vehicleTypeIds.add(pv.vehicle_type_id);
      if (pv.vehicle_id) vehicleIds.add(pv.vehicle_id);
    });

    const vendors = vendorIds.size
      ? await this.prisma.dvi_vendor_details.findMany({
          where: {
            vendor_id: { in: Array.from(vendorIds) },
            deleted: 0,
            status: 1,
          },
        })
      : [];
    const vendorById = new Map<number, (typeof vendors)[number]>();
    vendors.forEach((v) => vendorById.set(v.vendor_id, v));

    const vehicleTypes = vehicleTypeIds.size
      ? await this.prisma.dvi_vehicle_type.findMany({
          where: {
            vehicle_type_id: { in: Array.from(vehicleTypeIds) },
            deleted: 0,
            status: 1,
          },
        })
      : [];
    const vehicleTypeById = new Map<number, (typeof vehicleTypes)[number]>();
    vehicleTypes.forEach((vt) =>
      vehicleTypeById.set(vt.vehicle_type_id, vt),
    );

    const vehicles = vehicleIds.size
      ? await this.prisma.dvi_vehicle.findMany({
          where: {
            vehicle_id: { in: Array.from(vehicleIds) },
            deleted: 0,
            status: 1,
          },
        })
      : [];
    const vehicleById = new Map<number, (typeof vehicles)[number]>();
    vehicles.forEach((ve) => vehicleById.set(ve.vehicle_id, ve));

    // 2.4 Driver assignment per plan
    const driverAssignments =
      await this.prisma.dvi_confirmed_itinerary_vendor_driver_assigned.findMany(
        {
          where: {
            itinerary_plan_id: { in: effectivePlanIds },
            deleted: 0,
            status: 1,
          },
          orderBy: {
            driver_assigned_on: 'desc',
          },
        },
      );

    const driverIds = new Set<number>();
    const driverAssignmentByPlan = new Map<
      number,
      (typeof driverAssignments)[number]
    >();

    driverAssignments.forEach((da) => {
      if (!driverAssignmentByPlan.has(da.itinerary_plan_id)) {
        driverAssignmentByPlan.set(da.itinerary_plan_id, da);
      }
      if (da.driver_id) driverIds.add(da.driver_id);
    });

    const drivers = driverIds.size
      ? await this.prisma.dvi_driver_details.findMany({
          where: {
            driver_id: { in: Array.from(driverIds) },
            deleted: 0,
            status: 1,
          },
        })
      : [];
    const driverById = new Map<number, (typeof drivers)[number]>();
    drivers.forEach((d) => driverById.set(d.driver_id, d));

    // 2.5 Activities (for special_remarks)
    const routeActivities =
      await this.prisma.dvi_confirmed_itinerary_route_activity_details.findMany(
        {
          where: {
            itinerary_plan_ID: { in: effectivePlanIds },
            itinerary_route_ID: { in: routeIds },
            deleted: 0,
            status: 1,
          },
          orderBy: {
            activity_order: 'asc',
          },
        },
      );

    const activityIds = new Set<number>();
    type ActivityKey = string; // `${planId}:${routeId}`
    const activityByPlanRoute = new Map<
      ActivityKey,
      (typeof routeActivities)[number]
    >();

    routeActivities.forEach((ra) => {
      const key = this.mkPlanRouteKey(
        ra.itinerary_plan_ID,
        ra.itinerary_route_ID,
      );
      if (!activityByPlanRoute.has(key)) {
        activityByPlanRoute.set(key, ra); // first ordered activity only, like PHP helper
      }
      if (ra.activity_ID) activityIds.add(ra.activity_ID);
    });

    const activities = activityIds.size
      ? await this.prisma.dvi_activity.findMany({
          where: {
            activity_id: { in: Array.from(activityIds) },
            deleted: 0,
            status: 1,
          },
        })
      : [];
    const activityById = new Map<number, (typeof activities)[number]>();
    activities.forEach((a) => activityById.set(a.activity_id, a));

    // 2.6 Agent + Travel Expert (for TRAVEL EXPERT column)

    const agentIds = new Set<number>();
    plans.forEach((p) => {
      if (p.agent_id) agentIds.add(p.agent_id);
    });

    const agents = agentIds.size
      ? await this.prisma.dvi_agent.findMany({
          where: {
            agent_ID: { in: Array.from(agentIds) },
            deleted: 0,
            status: 1,
          },
          select: {
            agent_ID: true,
            agent_name: true,
            travel_expert_id: true,
          },
        })
      : [];
    const agentById = new Map<number, (typeof agents)[number]>();
    agents.forEach((a) => agentById.set(a.agent_ID, a));

    const travelExpertIds = new Set<number>();
    agents.forEach((a) => {
      if (a.travel_expert_id) {
        travelExpertIds.add(a.travel_expert_id);
      }
    });

    const travelExperts = travelExpertIds.size
      ? await this.prisma.dvi_staff_details.findMany({
          where: {
            staff_id: { in: Array.from(travelExpertIds) },
            deleted: 0,
            status: 1,
          },
          select: {
            staff_id: true,
            staff_name: true,
          },
        })
      : [];
    const travelExpertById = new Map<
      number,
      (typeof travelExperts)[number]
    >();
    travelExperts.forEach((te) =>
      travelExpertById.set(te.staff_id, te),
    );

    // 3) Build final rows like PHP
    let counter = 0;
    const rows: DailyMomentRowDto[] = [];

    for (const route of filteredRoutes) {
      const plan = planByPlanId.get(route.itinerary_plan_ID);
      if (!plan) continue;

      counter++;

      const itinerary_plan_ID = route.itinerary_plan_ID;
      const itinerary_route_ID = route.itinerary_route_ID;
      const itinerary_route_date = route.itinerary_route_date;
      const location_name = route.location_name ?? '';
      const next_visiting_location = route.next_visiting_location ?? '';

      // Guest + flights
      const customer = primaryCustomerByPlan.get(itinerary_plan_ID);
      const guest_name = customer?.customer_name ?? '';

      const arrival_flight_details = customer?.arrival_flight_details ?? '';
      const departure_flight_details =
        customer?.departure_flight_details ?? '';

      // Activity label + special instructions
      const actKey = this.mkPlanRouteKey(
        itinerary_plan_ID,
        itinerary_route_ID,
      );
      const ra = activityByPlanRoute.get(actKey);
      let specialRemarksFromActivity = '';

      if (ra && ra.activity_ID && activityById.has(ra.activity_ID)) {
        const act = activityById.get(ra.activity_ID)!;
        specialRemarksFromActivity = (act.activity_title ?? '').trim();
      }

      const special_instructions = (plan.special_instructions ?? '').trim();

      const isRemarksReal =
        specialRemarksFromActivity !== '' &&
        specialRemarksFromActivity !== '--';
      const isInstructionsReal =
        special_instructions !== '' && special_instructions !== '--';

      let special_remarks_final = '';
      if (isRemarksReal && isInstructionsReal) {
        special_remarks_final = `${specialRemarksFromActivity} / ${special_instructions}`;
      } else if (isRemarksReal) {
        special_remarks_final = specialRemarksFromActivity;
      } else if (isInstructionsReal) {
        special_remarks_final = special_instructions;
      }

      // Hotel name + meal plan
      const hotelKey = this.mkPlanRouteKey(
        itinerary_plan_ID,
        itinerary_route_ID,
      );
      const hotelRooms = hotelRoomByPlanRoute.get(hotelKey) ?? [];
      let hotel_name = '';
      let meal_breakfast_plan = '-';
      let meal_lunch_plan = '-';
      let meal_dinner_plan = '-';

      if (hotelRooms.length) {
        const hr0 = hotelRooms[0];
        if (hr0.hotel_id && hotelById.has(hr0.hotel_id)) {
          hotel_name = hotelById.get(hr0.hotel_id)!.hotel_name ?? '';
        }
        // If any room for that route has meal flags, switch from '-' to B/L/D
        if (hotelRooms.some((h) => h.breakfast_required === 1))
          meal_breakfast_plan = 'B';
        if (hotelRooms.some((h) => h.lunch_required === 1))
          meal_lunch_plan = 'L';
        if (hotelRooms.some((h) => h.dinner_required === 1))
          meal_dinner_plan = 'D';
      }

      // Vendor, vehicle type, vehicle no
      const vv = vendorVehByPlanRoute.get(hotelKey);
      let vendor_name = '';
      let vehicle_type_title = '';
      let vehicle_no = '';

      if (vv) {
        if (vv.vendor_id && vendorById.has(vv.vendor_id)) {
          vendor_name = vendorById.get(vv.vendor_id)!.vendor_name ?? '';
        }
        if (vv.vehicle_type_id && vehicleTypeById.has(vv.vehicle_type_id)) {
          vehicle_type_title =
            vehicleTypeById.get(vv.vehicle_type_id)!.vehicle_type_title ??
            '';
        }
        if (vv.vehicle_id && vehicleById.has(vv.vehicle_id)) {
          vehicle_no =
            vehicleById.get(vv.vehicle_id)!.registration_number ?? '';
        }
      }

      // Driver
      const driverAssignment =
        driverAssignmentByPlan.get(itinerary_plan_ID);
      let driver_name = '';
      let driver_mobile = '';

      if (
        driverAssignment &&
        driverAssignment.driver_id &&
        driverById.has(driverAssignment.driver_id)
      ) {
        const drv = driverById.get(driverAssignment.driver_id)!;
        driver_name = drv.driver_name ?? '';
        driver_mobile = drv.driver_primary_mobile_number ?? '';
      }

      // Agent + travel expert
      let agent_name = '';
      let travel_expert_name = '';

      if (plan.agent_id && agentById.has(plan.agent_id)) {
        const agent = agentById.get(plan.agent_id)!;
        agent_name = agent.agent_name ?? '';

        if (
          agent.travel_expert_id &&
          travelExpertById.has(agent.travel_expert_id)
        ) {
          const te = travelExpertById.get(agent.travel_expert_id)!;
          travel_expert_name = te.staff_name ?? '';
        }
      }

      // Trip type
      const tripStartDate = plan.trip_start_date_and_time
        ? this.formatDateYYYYMMDD(plan.trip_start_date_and_time)
        : '';
      const tripEndDate = plan.trip_end_date_and_time
        ? this.formatDateYYYYMMDD(plan.trip_end_date_and_time)
        : '';
      const routeDateYMD =
        this.formatDateYYYYMMDD(itinerary_route_date);

      let trip_type: 'Arrival' | 'Departure' | 'Ongoing';
      if (tripStartDate && routeDateYMD === tripStartDate) {
        trip_type = 'Arrival';
      } else if (tripEndDate && routeDateYMD === tripEndDate) {
        trip_type = 'Departure';
      } else {
        trip_type = 'Ongoing';
      }

      // Format route date as dd-mm-YYYY like PHP
      const route_date =
        this.formatDateDDMMYYYY(itinerary_route_date);

      const row: DailyMomentRowDto = {
        count: counter,
        guest_name: this.fieldOrDash(guest_name),
        quote_id: plan.itinerary_quote_ID ?? null,
        itinerary_plan_ID,
        route_date,
        trip_type,
        location_name: this.fieldOrDash(location_name),
        next_visiting_location:
          this.fieldOrDash(next_visiting_location),
        arrival_flight_details:
          this.fieldOrDash(arrival_flight_details),
        departure_flight_details:
          this.fieldOrDash(departure_flight_details),
        hotel_name: this.fieldOrDash(hotel_name),
        vehicle_type_title: this.fieldOrDash(vehicle_type_title),
        vendor_name: this.fieldOrDash(vendor_name),
        meal_plan: `${meal_breakfast_plan} ${meal_lunch_plan} ${meal_dinner_plan}`.trim(),
        vehicle_no: this.fieldOrDash(vehicle_no),
        driver_name: this.fieldOrDash(driver_name),
        driver_mobile: this.fieldOrDash(driver_mobile),
        special_remarks: this.fieldOrDash(special_remarks_final),
        travel_expert_name: this.fieldOrDash(travel_expert_name),
        agent_name: this.fieldOrDash(agent_name),
      };

      rows.push(row);
    }

    return rows;
  }

  /**
   * List extra charges for a given plan+route (car icon popup).
   */
  async listCharges(
    itineraryPlanId: number,
    itineraryRouteId: number,
  ): Promise<DailyMomentChargeRowDto[]> {
    if (!itineraryPlanId || !itineraryRouteId) {
      throw new BadRequestException(
        'itineraryPlanId and itineraryRouteId are required',
      );
    }

    const charges =
      await this.prisma.dvi_confirmed_itinerary_dailymoment_charge.findMany({
        where: {
          itinerary_plan_ID: itineraryPlanId,
          itinerary_route_ID: itineraryRouteId,
          deleted: 0,
          status: 1,
        },
        orderBy: {
          driver_charge_ID: 'asc',
        },
      });

    return charges.map((c) => ({
      driver_charge_ID: c.driver_charge_ID,
      itinerary_plan_ID: c.itinerary_plan_ID,
      itinerary_route_ID: c.itinerary_route_ID,
      charge_type: c.charge_type ?? '',
      charge_amount: c.charge_amount,
    }));
  }

  /**
   * Create / update an extra charge row (form behind car icon).
   */
  async upsertCharge(
    dto: UpsertDailyMomentChargeDto,
  ): Promise<DailyMomentChargeRowDto> {
    const {
      driverChargeId,
      itineraryPlanId,
      itineraryRouteId,
      chargeType,
      chargeAmount,
    } = dto;

    if (!itineraryPlanId || !itineraryRouteId) {
      throw new BadRequestException(
        'itineraryPlanId and itineraryRouteId are required',
      );
    }

    if (driverChargeId) {
      const updated =
        await this.prisma.dvi_confirmed_itinerary_dailymoment_charge.update(
          {
            where: { driver_charge_ID: driverChargeId },
            data: {
              itinerary_plan_ID: itineraryPlanId,
              itinerary_route_ID: itineraryRouteId,
              charge_type: chargeType,
              charge_amount: chargeAmount,
              updatedon: new Date(),
            },
          },
        );

      return {
        driver_charge_ID: updated.driver_charge_ID,
        itinerary_plan_ID: updated.itinerary_plan_ID,
        itinerary_route_ID: updated.itinerary_route_ID,
        charge_type: updated.charge_type ?? '',
        charge_amount: updated.charge_amount,
      };
    }

    const created =
      await this.prisma.dvi_confirmed_itinerary_dailymoment_charge.create({
        data: {
          itinerary_plan_ID: itineraryPlanId,
          itinerary_route_ID: itineraryRouteId,
          charge_type: chargeType,
          charge_amount: chargeAmount,
          createdon: new Date(),
          status: 1,
          deleted: 0,
        },
      });

    return {
      driver_charge_ID: created.driver_charge_ID,
      itinerary_plan_ID: created.itinerary_plan_ID,
      itinerary_route_ID: created.itinerary_route_ID,
      charge_type: created.charge_type ?? '',
      charge_amount: created.charge_amount,
    };
  }

  /**
   * Driver rating list (uses dvi_confirmed_itinerary_driver_feedback)
   */
  async listDriverRatings(
    itineraryPlanId: number,
  ): Promise<DriverRatingRowDto[]> {
    if (!itineraryPlanId) {
      throw new BadRequestException('itineraryPlanId is required');
    }

    const driverFeedback =
      await this.prisma.dvi_confirmed_itinerary_driver_feedback.findMany({
        where: {
          itinerary_plan_ID: itineraryPlanId,
          deleted: 0,
          status: 1,
        },
        orderBy: {
          driver_feedback_ID: 'asc',
        },
      });

    if (!driverFeedback.length) {
      return [];
    }

    const routeIds = Array.from(
      new Set(driverFeedback.map((f) => f.itinerary_route_ID)),
    );
    const routes =
      await this.prisma.dvi_confirmed_itinerary_route_details.findMany({
        where: {
          itinerary_plan_ID: itineraryPlanId,
          itinerary_route_ID: { in: routeIds },
          deleted: 0,
          status: 1,
        },
      });

    const routeById = new Map<number, (typeof routes)[number]>();
    routes.forEach((r) => routeById.set(r.itinerary_route_ID, r));

    const rows: DriverRatingRowDto[] = [];

    for (const fb of driverFeedback) {
      const route = routeById.get(fb.itinerary_route_ID);
      if (!route) continue;

      rows.push({
        driver_feedback_ID: fb.driver_feedback_ID,
        itinerary_plan_ID: fb.itinerary_plan_ID,
        itinerary_route_ID: fb.itinerary_route_ID,
        route_date: this.formatDateDDMMYYYY(route.itinerary_route_date),
        location_name: route.location_name ?? '',
        next_visiting_location: route.next_visiting_location ?? '',
        driver_rating: fb.driver_rating ?? '',
        driver_description: fb.driver_description ?? '',
      });
    }

    return rows;
  }

  /**
   * Guide rating list (route_guide_details + latest guide_review_details)
   */
  async listGuideRatings(
    itineraryPlanId: number,
  ): Promise<GuideRatingRowDto[]> {
    if (!itineraryPlanId) {
      throw new BadRequestException('itineraryPlanId is required');
    }

    const routeGuides =
      await this.prisma.dvi_confirmed_itinerary_route_guide_details.findMany({
        where: {
          itinerary_plan_ID: itineraryPlanId,
          deleted: 0,
          status: 1,
        },
        orderBy: {
          confirmed_route_guide_ID: 'asc',
        },
      });

    if (!routeGuides.length) {
      return [];
    }

    const routeIds = Array.from(
      new Set(routeGuides.map((g) => g.itinerary_route_ID)),
    );
    const guideIds = Array.from(
      new Set(routeGuides.map((g) => g.guide_id)),
    );

    const routes =
      await this.prisma.dvi_confirmed_itinerary_route_details.findMany({
        where: {
          itinerary_plan_ID: itineraryPlanId,
          itinerary_route_ID: { in: routeIds },
          deleted: 0,
          status: 1,
        },
      });
    const routeById = new Map<number, (typeof routes)[number]>();
    routes.forEach((r) => routeById.set(r.itinerary_route_ID, r));

    const guides = await this.prisma.dvi_guide_details.findMany({
      where: {
        guide_id: { in: guideIds },
        deleted: 0,
        status: 1,
      },
    });
    const guideById = new Map<number, (typeof guides)[number]>();
    guides.forEach((g) => guideById.set(g.guide_id, g));

    const guideReviews =
      await this.prisma.dvi_guide_review_details.findMany({
        where: {
          guide_id: { in: guideIds },
          deleted: 0,
          status: 1,
        },
        orderBy: {
          createdon: 'desc',
        },
      });

    const latestReviewByGuide = new Map<
      number,
      (typeof guideReviews)[number]
    >();
    guideReviews.forEach((rev) => {
      if (!latestReviewByGuide.has(rev.guide_id)) {
        latestReviewByGuide.set(rev.guide_id, rev);
      }
    });

    const rows: GuideRatingRowDto[] = [];

    for (const rg of routeGuides) {
      const route = routeById.get(rg.itinerary_route_ID);
      if (!route) continue;

      const guide = guideById.get(rg.guide_id);
      const review = latestReviewByGuide.get(rg.guide_id);

      rows.push({
        guide_review_id: review?.guide_review_id ?? 0,
        itinerary_plan_ID: rg.itinerary_plan_ID,
        itinerary_route_ID: rg.itinerary_route_ID,
        route_date: this.formatDateDDMMYYYY(route.itinerary_route_date),
        location_name: route.location_name ?? '',
        next_visiting_location: route.next_visiting_location ?? '',
        guide_id: rg.guide_id,
        guide_name: guide?.guide_name ?? '',
        guide_rating: review?.guide_rating ?? '',
        guide_description: review?.guide_description ?? '',
      });
    }

    return rows;
  }

  // ========= HELPER METHODS =========

  private parseDate(input: string): Date {
    const trimmed = input.trim();

    // Support DD-MM-YYYY (from old PHP UI)
    const ddmmyyyy = /^(\d{2})-(\d{2})-(\d{4})$/;
    const m = trimmed.match(ddmmyyyy);
    if (m) {
      const [_, dd, mm, yyyy] = m;
      return new Date(Date.UTC(Number(yyyy), Number(mm) - 1, Number(dd)));
    }

    // Fallback: let JS parse ISO-like inputs
    const d = new Date(trimmed);
    if (isNaN(d.getTime())) {
      throw new BadRequestException(`Invalid date: ${input}`);
    }
    return d;
  }

  private mkPlanRouteKey(planId: number, routeId: number): string {
    return `${planId}:${routeId}`;
  }

  private formatDateYYYYMMDD(date: Date | null): string {
    if (!date) return '';
    const d = new Date(date);
    const y = d.getFullYear();
    const m = String(d.getMonth() + 1).padStart(2, '0');
    const day = String(d.getDate()).padStart(2, '0');
    return `${y}-${m}-${day}`;
  }

  private formatDateDDMMYYYY(date: Date | null): string {
    if (!date) return '';
    const d = new Date(date);
    const y = d.getFullYear();
    const m = String(d.getMonth() + 1).padStart(2, '0');
    const day = String(d.getDate()).padStart(2, '0');
    return `${day}-${m}-${y}`;
  }

  private fieldOrDash(value: string | null | undefined): string {
    if (value === null || value === undefined) return '--';
    const plain = String(value).trim();
    return plain === '' ? '--' : value;
  }
}
