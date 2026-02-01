import { Injectable, BadRequestException } from '@nestjs/common';
import { PrismaService } from '../../../prisma.service';

/**
 * Itinerary Activity Service
 * Handles all activity-related operations for itineraries
 * - Add/delete activities
 * - Get available activities
 */
@Injectable()
export class ItineraryActivityService {
  constructor(private readonly prisma: PrismaService) {}

  /**
   * Get available activities for a route
   */
  async getAvailableActivities(routeId: number) {
    const route = await (this.prisma as any).dvi_itinerary_route_details.findFirst({
      where: { itinerary_route_ID: routeId, deleted: 0 },
    });

    if (!route || !route.location_id) return [];

    const location = await (this.prisma as any).dvi_stored_locations.findFirst({
      where: { location_ID: Number(route.location_id), deleted: 0 },
    });

    if (!location) return [];

    const sourceName: string | null = (location as any).source_location ?? null;
    const destName: string | null = (location as any).destination_location ?? null;

    const directDestination = Number(route.direct_to_next_visiting_place || 0) === 1;

    // Fetch activities by city name
    const fetchActivities = async (cityName: string | null) => {
      if (!cityName) return [];
      return await (this.prisma as any).dvi_activity.findMany({
        where: {
          status: 1,
          deleted: 0,
          activity_location: { contains: cityName },
        },
        select: {
          activity_ID: true,
          activity_name: true,
          activity_cost: true,
          activity_duration: true,
          activity_location: true,
          activity_priority: true,
        },
        orderBy: [{ activity_priority: "desc" }, { activity_ID: "asc" }],
      });
    };

    const sourceActivities = await fetchActivities(sourceName);
    const destActivities = await fetchActivities(destName);

    // Build final ordered list with 3-chunk interleaving
    const seen = new Set<number>();
    const ordered: any[] = [];

    const pushUnique = (a: any) => {
      const id = Number(a?.activity_ID);
      if (!id || seen.has(id)) return;
      seen.add(id);
      ordered.push(a);
    };

    if (directDestination) {
      // direct = true => destination only
      for (const a of destActivities) pushUnique(a);
    } else {
      // direct = false => interleave 3-by-3 source/dest
      const CHUNK = 3;
      let i = 0;
      let j = 0;

      while (i < sourceActivities.length || j < destActivities.length) {
        for (let k = 0; k < CHUNK && i < sourceActivities.length; k++, i++) pushUnique(sourceActivities[i]);
        for (let k = 0; k < CHUNK && j < destActivities.length; k++, j++) pushUnique(destActivities[j]);
      }
    }

    if (ordered.length === 0) return [];

    // Return formatted activities
    return ordered.map((a: any) => ({
      id: a.activity_ID,
      name: a.activity_name,
      amount: a.activity_cost || 0,
      timeSpend: a.activity_duration ? new Date(a.activity_duration).getUTCHours() : 0,
      location: a.activity_location || null,
    }));
  }

  /**
   * Add an activity to an itinerary route
   */
  async addActivity(data: {
    planId: number;
    routeId: number;
    activityId: number;
  }) {
    const userId = 1;

    // Check if activity already exists
    const existing = await (this.prisma as any).dvi_itinerary_route_activity_details.findFirst({
      where: {
        itinerary_plan_ID: data.planId,
        itinerary_route_ID: data.routeId,
        activity_ID: data.activityId,
        deleted: 0,
      },
    });

    if (existing) {
      throw new BadRequestException('Activity already added to this route');
    }

    // Insert the activity record
    const activity = await (this.prisma as any).dvi_activity.findUnique({
      where: { activity_ID: data.activityId },
    });

    if (!activity) {
      throw new BadRequestException('Activity not found');
    }

    await (this.prisma as any).dvi_itinerary_route_activity_details.create({
      data: {
        itinerary_plan_ID: data.planId,
        itinerary_route_ID: data.routeId,
        activity_ID: data.activityId,
        item_type: 5, // Activity type
        activity_cost: activity.activity_cost || 0,
        activity_duration: activity.activity_duration || null,
        activity_guest_count: 0,
        activity_per_person_cost: activity.activity_cost || 0,
        createdby: userId,
        createdon: new Date(),
        status: 1,
        deleted: 0,
      },
    });

    return {
      success: true,
      message: 'Activity added successfully',
    };
  }

  /**
   * Delete an activity from an itinerary route
   */
  async deleteActivity(
    planId: number,
    routeId: number,
    activityId: number,
  ) {
    const activity = await (this.prisma as any).dvi_itinerary_route_activity_details.findFirst({
      where: {
        itinerary_plan_ID: planId,
        itinerary_route_ID: routeId,
        activity_ID: activityId,
        deleted: 0,
      },
    });

    if (!activity) {
      throw new BadRequestException('Activity not found');
    }

    // Hard delete the activity record
    await (this.prisma as any).dvi_itinerary_route_activity_details.deleteMany({
      where: {
        itinerary_plan_ID: planId,
        itinerary_route_ID: routeId,
        activity_ID: activityId,
      },
    });

    return {
      success: true,
      message: 'Activity deleted successfully',
    };
  }
}
