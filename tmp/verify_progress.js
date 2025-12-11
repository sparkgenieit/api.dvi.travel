const { PrismaClient } = require('@prisma/client');
const prisma = new PrismaClient();

async function main() {
  console.log('\n=== CHECKING PLAN 5 PROGRESS ===\n');

  // 1. Vendor eligible list
  const vendorEligible = await prisma.dvi_itinerary_plan_vendor_eligible_list.count({
    where: { itinerary_plan_id: 5 }
  });
  console.log(`✓ Vendor Eligible List: ${vendorEligible} rows`);

  // 2. Vendor vehicle details (this is where parking charges are)
  const vendorVehicle = await prisma.dvi_itinerary_plan_vendor_vehicle_details.findFirst({
    where: { itinerary_plan_id: 5 },
    select: {
      vehicle_parking_charges: true,
      vehicle_toll_charges: true,
      vehicle_permit_charges: true,
      total_vehicle_amount: true
    }
  });
  console.log(`✓ Vendor Vehicle Details:`, vendorVehicle);

  // 3. Parking charge rows
  const parkingCount = await prisma.dvi_itinerary_route_hotspot_parking_charge.count({
    where: { itinerary_plan_ID: 5 }
  });
  console.log(`✓ Parking Charge Rows: ${parkingCount} rows`);

  // 4. Hotel data
  const hotelRoom = await prisma.dvi_itinerary_plan_hotel_room_details.findFirst({
    where: { itinerary_plan_id: 5 },
    select: {
      hotel_id: true,
      room_id: true,
      room_rate: true,
      total_breafast_cost: true
    }
  });
  console.log(`✓ Hotel Room Details:`, hotelRoom);

  console.log('\n=== COMPARISON WITH PLAN 2 ===\n');
  
  const plan2Parking = await prisma.dvi_itinerary_route_hotspot_parking_charge.count({
    where: { itinerary_plan_ID: 2 }
  });
  console.log(`Plan 2 parking charges: ${plan2Parking} rows`);
  console.log(`Plan 5 parking charges: ${parkingCount} rows`);
  
  const plan2Vendor = await prisma.dvi_itinerary_plan_vendor_vehicle_details.findFirst({
    where: { itinerary_plan_id: 2 },
    select: { vehicle_parking_charges: true }
  });
  console.log(`\nPlan 2 parking total: ${plan2Vendor?.vehicle_parking_charges}`);
  console.log(`Plan 5 parking total: ${vendorVehicle?.vehicle_parking_charges}`);
}

main()
  .catch(e => console.error('ERROR:', e.message))
  .finally(() => prisma.$disconnect());
