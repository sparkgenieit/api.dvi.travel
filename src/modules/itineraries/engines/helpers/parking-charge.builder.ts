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
  /**
   * Returns an array of ParkingChargeRow, one for each vendor vehicle for the plan (PHP parity).
   */
  async buildForHotspot(
    tx: Tx,
    opts: {
      planId: number;
      routeId: number;
      hotspotId: number;
      userId: number;
    },
  ): Promise<ParkingChargeRow[]> {
    const { planId, routeId, hotspotId, userId } = opts;

    const rows: ParkingChargeRow[] = [];
    try {
      // 1. Get all required vehicle types and total quantities for the plan (PHP parity)
      const planVehiclesTable = (tx as any).dvi_itinerary_plan_vehicle_details;
      if (!planVehiclesTable) {
        console.log(
          "[ParkingChargeBuilder] dvi_itinerary_plan_vehicle_details table not available",
        );
        return rows;
      }

      // Group by vehicle_type_id, sum vehicle_count for the whole plan (not just this route)
      const vehicleTypeAgg = await planVehiclesTable.groupBy({
        by: ["vehicle_type_id"],
        where: {
          itinerary_plan_id: planId,
          deleted: 0,
          status: 1,
        },
        _sum: { vehicle_count: true },
      });

      if (!vehicleTypeAgg || vehicleTypeAgg.length === 0) {
        // No vehicles for this plan
        return rows;
      }

      // Get parking charges from vehicle parking charges table
      const parkingChargesTable = (tx as any).dvi_hotspot_vehicle_parking_charges;
      if (!parkingChargesTable) {
        console.log(
          "[ParkingChargeBuilder] dvi_hotspot_vehicle_parking_charges table not available in transaction",
        );
        return rows;
      }

      // For each vehicle type required for this plan, get the parking charge for this hotspot and vehicle type
      for (const vt of vehicleTypeAgg) {
        const vehicleTypeId = vt.vehicle_type_id ?? 0;
        const vehicleQty = vt._sum?.vehicle_count ?? 1;
        if (!vehicleTypeId || vehicleQty <= 0) continue;

        // Find parking charge for this hotspot and vehicle type
        const parkingCharges = await parkingChargesTable.findFirst({
          where: {
            hotspot_id: BigInt(hotspotId),
            vehicle_type_id: vehicleTypeId,
            deleted: 0,
            status: 1,
          },
        });

        if (!parkingCharges) {
          // No parking charge for this vehicle type at this hotspot
          continue;
        }

        const unitCharge = Number(parkingCharges.parking_charge ?? 0);
        const parkingAmount = unitCharge * vehicleQty;

        const now = new Date();

        const row: ParkingChargeRow = {
          itinerary_plan_ID: planId,
          itinerary_route_ID: routeId,
          hotspot_ID: hotspotId,
          vehicle_type: vehicleTypeId,
          vehicle_qty: vehicleQty,
          parking_charges_amt: parkingAmount,
          createdby: userId,
          createdon: now,
          updatedon: null,
          status: 1,
          deleted: 0,
        };
        rows.push(row);
      }

      return rows;
    } catch (err) {
      console.error("[ParkingChargeBuilder] Error building parking charge:", err);
      return rows;
    }
  }
}
