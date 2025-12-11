const { PrismaClient } = require('@prisma/client');
const prisma = new PrismaClient();

async function main() {
  console.log('\n=== COMPARISON SUMMARY ===\n');

  // Hotels
  const p5Hotels = await prisma.dvi_itinerary_plan_hotel_details.count({
    where: { itinerary_plan_id: 5 }
  });
  const p2Hotels = await prisma.dvi_itinerary_plan_hotel_details.count({
    where: { itinerary_plan_id: 2 }
  });
  console.log(`Hotels: Plan 2 = ${p2Hotels}, Plan 5 = ${p5Hotels}`);

  // Hotel Rooms
  const p5Rooms = await prisma.dvi_itinerary_plan_hotel_room_details.count({
    where: { itinerary_plan_id: 5 }
  });
  const p2Rooms = await prisma.dvi_itinerary_plan_hotel_room_details.count({
    where: { itinerary_plan_id: 2 }
  });
  console.log(`Hotel Rooms: Plan 2 = ${p2Rooms}, Plan 5 = ${p5Rooms}`);

  // Vendor Eligible
  const p5Vendor = await prisma.dvi_itinerary_plan_vendor_eligible_list.count({
    where: { itinerary_plan_id: 5 }
  });
  const p2Vendor = await prisma.dvi_itinerary_plan_vendor_eligible_list.count({
    where: { itinerary_plan_id: 2 }
  });
  console.log(`Vendor Eligible: Plan 2 = ${p2Vendor}, Plan 5 = ${p5Vendor}`);

  // Vendor Vehicles
  const p5VV = await prisma.dvi_itinerary_plan_vendor_vehicle_details.count({
    where: { itinerary_plan_id: 5 }
  });
  const p2VV = await prisma.dvi_itinerary_plan_vendor_vehicle_details.count({
    where: { itinerary_plan_id: 2 }
  });
  console.log(`Vendor Vehicles: Plan 2 = ${p2VV}, Plan 5 = ${p5VV}`);

  // Parking
  const p5Parking = await prisma.dvi_itinerary_route_hotspot_parking_charge.count({
    where: { itinerary_plan_ID: 5 }
  });
  const p2Parking = await prisma.dvi_itinerary_route_hotspot_parking_charge.count({
    where: { itinerary_plan_ID: 2 }
  });
  console.log(`Parking Charges: Plan 2 = ${p2Parking}, Plan 5 = ${p5Parking}`);

  // Travellers
  const p5Travellers = await prisma.dvi_itinerary_traveller_details.count({
    where: { itinerary_plan_ID: 5 }
  });
  const p2Travellers = await prisma.dvi_itinerary_traveller_details.count({
    where: { itinerary_plan_ID: 2 }
  });
  console.log(`Travellers: Plan 2 = ${p2Travellers}, Plan 5 = ${p5Travellers}`);

  await prisma.$disconnect();
}

main().catch(console.error);
