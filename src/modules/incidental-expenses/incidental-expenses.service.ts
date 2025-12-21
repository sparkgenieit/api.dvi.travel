import { Injectable, NotFoundException } from '@nestjs/common';
import { PrismaService } from '../../prisma.service';

@Injectable()
export class IncidentalExpensesService {
  constructor(private prisma: PrismaService) {}

  async getAvailableComponents(itineraryPlanId: number) {
    const plan = await this.prisma.dvi_confirmed_itinerary_plan_details.findFirst({
      where: { itinerary_plan_ID: itineraryPlanId, deleted: 0 },
    });

    if (!plan) {
      throw new NotFoundException('Confirmed itinerary plan not found');
    }

    // Get itinerary preference and entry ticket requirement
    const itineraryPlan = await this.prisma.dvi_itinerary_plan_details.findUnique({
      where: { itinerary_plan_ID: itineraryPlanId },
      select: { entry_ticket_required: true },
    });

    const itineraryPreference = plan.itinerary_preference;
    const entryTicketRequired = itineraryPlan?.entry_ticket_required || 0;

    // Check for guides
    const guides = await this.prisma.dvi_confirmed_itinerary_route_guide_details.findMany({
      where: { itinerary_plan_ID: itineraryPlanId, deleted: 0, status: 1, guide_id: { not: 0 } },
    });

    // Check for hotspots
    const hotspots = await this.prisma.dvi_confirmed_itinerary_route_hotspot_details.findMany({
      where: { itinerary_plan_ID: itineraryPlanId, deleted: 0, status: 1, hotspot_ID: { not: 0 }, item_type: 4 },
    });

    // Check for activities
    const activities = await this.prisma.dvi_confirmed_itinerary_route_activity_details.findMany({
      where: { itinerary_plan_ID: itineraryPlanId, deleted: 0, status: 1, activity_ID: { not: 0 } },
    });

    // Check for hotels
    const hotels = await this.prisma.dvi_confirmed_itinerary_plan_hotel_details.findMany({
      where: { itinerary_plan_id: itineraryPlanId, deleted: 0, status: 1, hotel_id: { not: 0 } },
    });

    // Check for vendors
    const vendors = await this.prisma.dvi_confirmed_itinerary_plan_vendor_eligible_list.findMany({
      where: { itinerary_plan_id: itineraryPlanId, deleted: 0, status: 1, vendor_id: { not: 0 }, itineary_plan_assigned_status: 1 },
    });

    const availableTypes = [];
    if (guides.length > 0) availableTypes.push({ id: 1, label: 'Guide' });
    if (hotspots.length > 0 && entryTicketRequired === 1) availableTypes.push({ id: 2, label: 'Hotspot' });
    if (activities.length > 0) availableTypes.push({ id: 3, label: 'Activity' });
    if (itineraryPreference === 1 || itineraryPreference === 3) availableTypes.push({ id: 4, label: 'Hotel' });
    if (itineraryPreference === 2 || itineraryPreference === 3) availableTypes.push({ id: 5, label: 'Vendor' });

    return {
      availableTypes,
      guides: await Promise.all(guides.map(async (g) => {
        const guide = await this.prisma.dvi_guide_details.findUnique({ where: { guide_id: g.guide_id }, select: { guide_name: true } });
        const route = await this.prisma.dvi_confirmed_itinerary_route_details.findFirst({ where: { itinerary_plan_ID: itineraryPlanId, itinerary_route_ID: g.itinerary_route_ID } });
        return {
          id: g.confirmed_route_guide_ID,
          name: `${guide?.guide_name || 'N/A'} (${route?.itinerary_route_date ? new Date(route.itinerary_route_date).toLocaleDateString() : 'N/A'})`,
        };
      })),
      hotspots: await Promise.all(hotspots.map(async (h) => {
        const hotspot = await this.prisma.dvi_hotspot_place.findUnique({ where: { hotspot_ID: h.hotspot_ID }, select: { hotspot_name: true } });
        const route = await this.prisma.dvi_confirmed_itinerary_route_details.findFirst({ where: { itinerary_plan_ID: itineraryPlanId, itinerary_route_ID: h.itinerary_route_ID } });
        return {
          id: h.confirmed_route_hotspot_ID,
          name: `${hotspot?.hotspot_name || 'N/A'} (${route?.itinerary_route_date ? new Date(route.itinerary_route_date).toLocaleDateString() : 'N/A'})`,
        };
      })),
      activities: await Promise.all(activities.map(async (a) => {
        const activity = await this.prisma.dvi_activity.findUnique({ where: { activity_id: a.activity_ID }, select: { activity_title: true } });
        const route = await this.prisma.dvi_confirmed_itinerary_route_details.findFirst({ where: { itinerary_plan_ID: itineraryPlanId, itinerary_route_ID: a.itinerary_route_ID } });
        return {
          id: a.confirmed_route_activity_ID,
          name: `${activity?.activity_title || 'N/A'} (${route?.itinerary_route_date ? new Date(route.itinerary_route_date).toLocaleDateString() : 'N/A'})`,
        };
      })),
      hotels: await Promise.all(hotels.map(async (h) => {
        const hotel = await this.prisma.dvi_hotel.findUnique({ where: { hotel_id: h.hotel_id }, select: { hotel_name: true } });
        const route = await this.prisma.dvi_confirmed_itinerary_route_details.findFirst({ where: { itinerary_plan_ID: itineraryPlanId, itinerary_route_ID: h.itinerary_route_id } });
        return {
          id: h.confirmed_itinerary_plan_hotel_details_ID,
          name: `${hotel?.hotel_name || 'N/A'} - ${route?.itinerary_route_date ? new Date(route.itinerary_route_date).toLocaleDateString() : 'N/A'}`,
        };
      })),
      vendors: await Promise.all(vendors.map(async (v) => {
        const vendor = await this.prisma.dvi_vendor_details.findUnique({ where: { vendor_id: v.vendor_id }, select: { vendor_name: true } });
        const vehicleType = await this.prisma.dvi_vehicle_type.findUnique({ where: { vehicle_type_id: v.vehicle_type_id }, select: { vehicle_type_title: true } });
        return {
          id: v.itinerary_plan_vendor_eligible_ID,
          name: `${vendor?.vendor_name || 'N/A'} (${vehicleType?.vehicle_type_title || 'N/A'})`,
        };
      })),
    };
  }

  async getAvailableMargin(itineraryPlanId: number, componentType: number, componentId?: number) {
    // Check if already exists in main table
    const existing = await this.prisma.dvi_confirmed_itinerary_incidental_expenses.findFirst({
      where: { itinerary_plan_id: itineraryPlanId, component_type: componentType, deleted: 0, status: 1 },
    });

    if (existing) {
      return { total_avail_cost: Math.round(existing.total_balance) };
    }

    // If not, calculate from scratch
    const plan = await this.prisma.dvi_confirmed_itinerary_plan_details.findFirst({
      where: { itinerary_plan_ID: itineraryPlanId, deleted: 0 },
    });

    if (!plan) throw new NotFoundException('Plan not found');

    const agentMarginCharges = plan.itinerary_agent_margin_charges || 0;

    if (componentType === 1 || componentType === 2 || componentType === 3) {
      // Guide, Hotspot, Activity share the agent margin
      const hasGuide = (await this.prisma.dvi_confirmed_itinerary_route_guide_details.count({ where: { itinerary_plan_ID: itineraryPlanId, deleted: 0, status: 1, guide_id: { not: 0 } } })) > 0;
      const hasHotspot = (await this.prisma.dvi_confirmed_itinerary_route_hotspot_details.count({ where: { itinerary_plan_ID: itineraryPlanId, deleted: 0, status: 1, hotspot_ID: { not: 0 }, item_type: 4 } })) > 0;
      const hasActivity = (await this.prisma.dvi_confirmed_itinerary_route_activity_details.count({ where: { itinerary_plan_ID: itineraryPlanId, deleted: 0, status: 1, activity_ID: { not: 0 } } })) > 0;

      let divisor = 0;
      if (hasGuide) divisor++;
      if (hasHotspot) divisor++;
      if (hasActivity) divisor++;

      const share = divisor > 0 ? Math.round(agentMarginCharges / divisor) : 0;
      return { total_avail_cost: share };
    } else if (componentType === 4) {
      // Hotel
      if (!componentId) return { total_avail_cost: 0 };
      const hotelDetail = await this.prisma.dvi_confirmed_itinerary_plan_hotel_details.findUnique({
        where: { confirmed_itinerary_plan_hotel_details_ID: componentId },
      });
      return { total_avail_cost: Math.round(hotelDetail?.hotel_margin_rate || 0) };
    } else if (componentType === 5) {
      // Vendor
      if (!componentId) return { total_avail_cost: 0 };
      const vendorDetail = await this.prisma.dvi_confirmed_itinerary_plan_vendor_eligible_list.findUnique({
        where: { confirmed_itinerary_plan_vendor_eligible_ID: componentId },
      });
      return { total_avail_cost: Math.round(vendorDetail?.vendor_margin_amount || 0) };
    }

    return { total_avail_cost: 0 };
  }

  async addIncidentalExpense(data: {
    itineraryPlanId: number;
    componentType: number;
    componentId: number;
    amount: number;
    reason: string;
    createdBy: number;
  }) {
    const { itineraryPlanId, componentType, componentId, amount, reason, createdBy } = data;

    // 1. Determine main component ID and itinerary route ID
    let mainComponentId = 0;
    let itineraryRouteId = 0;
    let confirmedRouteGuideId = 0;
    let confirmedRouteHotspotId = 0;
    let confirmedRouteActivityId = 0;
    let confirmedHotelDetailsId = 0;
    let confirmedVendorEligibleId = 0;

    if (componentType === 1) {
      const g = await this.prisma.dvi_confirmed_itinerary_route_guide_details.findUnique({ where: { confirmed_route_guide_ID: componentId } });
      mainComponentId = g?.guide_id || 0;
      itineraryRouteId = g?.itinerary_route_ID || 0;
      confirmedRouteGuideId = componentId;
    } else if (componentType === 2) {
      const h = await this.prisma.dvi_confirmed_itinerary_route_hotspot_details.findUnique({ where: { confirmed_route_hotspot_ID: componentId } });
      mainComponentId = h?.hotspot_ID || 0;
      itineraryRouteId = h?.itinerary_route_ID || 0;
      confirmedRouteHotspotId = componentId;
    } else if (componentType === 3) {
      const a = await this.prisma.dvi_confirmed_itinerary_route_activity_details.findUnique({ where: { confirmed_route_activity_ID: componentId } });
      mainComponentId = a?.activity_ID || 0;
      itineraryRouteId = a?.itinerary_route_ID || 0;
      confirmedRouteActivityId = componentId;
    } else if (componentType === 4) {
      const h = await this.prisma.dvi_confirmed_itinerary_plan_hotel_details.findUnique({ where: { confirmed_itinerary_plan_hotel_details_ID: componentId } });
      mainComponentId = h?.hotel_id || 0;
      itineraryRouteId = h?.itinerary_route_id || 0;
      confirmedHotelDetailsId = componentId;
    } else if (componentType === 5) {
      const v = await this.prisma.dvi_confirmed_itinerary_plan_vendor_eligible_list.findUnique({ where: { confirmed_itinerary_plan_vendor_eligible_ID: componentId } });
      mainComponentId = v?.vendor_id || 0;
      confirmedVendorEligibleId = componentId;
    }

    // 2. Get total amount (margin)
    const marginInfo = await this.getAvailableMargin(itineraryPlanId, componentType, componentId);
    const totalMargin = marginInfo.total_avail_cost;

    // 3. Check if main record exists
    let mainRecord = await this.prisma.dvi_confirmed_itinerary_incidental_expenses.findFirst({
      where: { itinerary_plan_id: itineraryPlanId, component_type: componentType, component_id: mainComponentId, deleted: 0 },
    });

    if (!mainRecord) {
      const totalBalance = totalMargin - amount;
      mainRecord = await this.prisma.dvi_confirmed_itinerary_incidental_expenses.create({
        data: {
          itinerary_plan_id: itineraryPlanId,
          component_type: componentType,
          component_id: mainComponentId,
          total_amount: totalMargin,
          total_payed: amount,
          total_balance: totalBalance,
          status: 1,
          deleted: 0,
          createdby: createdBy,
          createdon: new Date(),
        },
      });
    } else {
      const newTotalPayed = (mainRecord.total_payed || 0) + amount;
      const newTotalBalance = (mainRecord.total_balance || 0) - amount;
      mainRecord = await this.prisma.dvi_confirmed_itinerary_incidental_expenses.update({
        where: { confirmed_itinerary_incidental_expenses_main_ID: mainRecord.confirmed_itinerary_incidental_expenses_main_ID },
        data: {
          total_payed: newTotalPayed,
          total_balance: newTotalBalance,
          updatedon: new Date(),
        },
      });
    }

    // 4. Create history record
    await this.prisma.dvi_confirmed_itinerary_incidental_expenses_history.create({
      data: {
        confirmed_itinerary_incidental_expenses_main_ID: mainRecord.confirmed_itinerary_incidental_expenses_main_ID,
        itinerary_plan_id: itineraryPlanId,
        itinerary_route_id: itineraryRouteId,
        confirmed_route_guide_ID: confirmedRouteGuideId,
        confirmed_route_hotspot_ID: confirmedRouteHotspotId,
        confirmed_route_activity_ID: confirmedRouteActivityId,
        confirmed_itinerary_plan_hotel_details_ID: confirmedHotelDetailsId,
        confirmed_itinerary_plan_vendor_eligible_ID: confirmedVendorEligibleId,
        component_type: componentType,
        component_id: mainComponentId,
        incidental_amount: amount,
        reason: reason,
        status: 1,
        deleted: 0,
        createdby: createdBy,
        createdon: new Date(),
      },
    });

    return { success: true };
  }

  async getIncidentalHistory(itineraryPlanId: number) {
    return this.prisma.dvi_confirmed_itinerary_incidental_expenses_history.findMany({
      where: { itinerary_plan_id: itineraryPlanId, deleted: 0 },
      orderBy: { createdon: 'desc' },
    });
  }

  async deleteIncidentalHistory(historyId: number) {
    // Note: PHP does a hard delete
    await this.prisma.dvi_confirmed_itinerary_incidental_expenses_history.delete({
      where: { confirmed_itinerary_incidental_expenses_history_ID: historyId },
    });
    return { success: true };
  }
}
