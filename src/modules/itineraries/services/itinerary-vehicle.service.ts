import { Injectable } from '@nestjs/common';
import { PrismaService } from '../../../prisma.service';

/**
 * Itinerary Vehicle Service
 * Handles vehicle-related operations for itineraries
 * - Select/change vehicle vendor
 */
@Injectable()
export class ItineraryVehicleService {
  constructor(private readonly prisma: PrismaService) {}

  /**
   * Select/change vehicle vendor for a plan
   */
  async selectVehicleVendor(data: {
    planId: number;
    vehicleTypeId: number;
    vendorEligibleId: number;
  }) {
    // First, reset all vendors for this vehicle type to unassigned (0)
    await (this.prisma as any).dvi_itinerary_plan_vendor_eligible_list.updateMany({
      where: {
        itinerary_plan_id: data.planId,
        vehicle_type_id: data.vehicleTypeId,
      },
      data: {
        itineary_plan_assigned_status: 0,
      },
    });

    // Then, set the selected vendor to assigned (1)
    await (this.prisma as any).dvi_itinerary_plan_vendor_eligible_list.update({
      where: {
        itinerary_plan_vendor_eligible_ID: data.vendorEligibleId,
      },
      data: {
        itineary_plan_assigned_status: 1,
      },
    });

    return {
      success: true,
      message: 'Vehicle vendor selected successfully',
    };
  }
}
