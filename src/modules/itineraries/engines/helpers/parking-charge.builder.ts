// FILE: src/modules/itineraries/engines/helpers/parking-charge.builder.ts

import { Prisma } from "@prisma/client";

type Tx = Prisma.TransactionClient;

export interface ParkingChargeRow {
  itinerary_plan_ID: number;
  itinerary_route_ID: number;
  hotspot_ID: number;
  vehicle_type: number;
  vehicle_qty: number;
  parking_charges_amt: number;
  createdby: number;
  createdon: Date;
  updatedon: Date | null;
  status: 0 | 1;
  deleted: 0 | 1;
}

/**
 * Builds rows for dvi_itinerary_route_hotspot_parking_charge
 * for hotspots that require parking.
 */
export class ParkingChargeBuilder {
  async buildForHotspot(
    tx: Tx,
    opts: {
      planId: number;
      routeId: number;
      hotspotId: number;
      userId: number;
    },
  ): Promise<ParkingChargeRow | null> {
    const { planId, routeId, hotspotId, userId } = opts;

    try {
      // Get vehicle info from confirmed itinerary vehicle details table
      const vehiclesTable = (tx as any).dvi_accounts_itinerary_vehicle_details;
      if (!vehiclesTable) {
        console.log(
          "[ParkingChargeBuilder] dvi_accounts_itinerary_vehicle_details table not available in transaction",
        );
        return null;
      }

      const vehicle = await vehiclesTable.findFirst({
        where: { itinerary_plan_ID: planId, deleted: 0, status: 1 },
      });

      if (!vehicle) {
        console.log(`[ParkingChargeBuilder] No vehicles found for plan ${planId}`);
        return null;
      }

      // Use total_vehicle_qty from accounts table or default to 1
      const vehicleQty = vehicle.total_vehicle_qty ?? 1;

      // Get parking charges from vehicle parking charges table
      const parkingChargesTable = (tx as any).dvi_hotspot_vehicle_parking_charges;
      if (!parkingChargesTable) {
        console.log(
          "[ParkingChargeBuilder] dvi_hotspot_vehicle_parking_charges table not available in transaction",
        );
        return null;
      }

      const parkingCharges = await parkingChargesTable.findFirst({
        where: {
          hotspot_id: BigInt(hotspotId),
          deleted: 0,
          status: 1,
        },
      });

      if (!parkingCharges) {
        console.log(
          `[ParkingChargeBuilder] No parking charges found for hotspot ${hotspotId}`,
        );
        return null;
      }

      const unitCharge = Number(parkingCharges.parking_charge ?? 0);
      const parkingAmount = unitCharge * vehicleQty;

      const now = new Date();

      const row: ParkingChargeRow = {
        itinerary_plan_ID: planId,
        itinerary_route_ID: routeId,
        hotspot_ID: hotspotId,
        vehicle_type: 0,
        vehicle_qty: vehicleQty,
        parking_charges_amt: parkingAmount,
        createdby: userId,
        createdon: now,
        updatedon: null,
        status: 1,
        deleted: 0,
      };

      return row;
    } catch (err) {
      console.error("[ParkingChargeBuilder] Error building parking charge:", err);
      return null;
    }
  }
}
