const { PrismaClient } = require('@prisma/client');
const prisma = new PrismaClient();

async function tracePlan2() {
  // Check when Plan 2 was created
  const plan2 = await prisma.dvi_itinerary_plans.findFirst({
    where: { itinerary_plan_id: 2 },
    select: {
      itinerary_plan_id: true,
      createdby: true,
      createdon: true,
      quote_id: true,
    },
  });
  
  console.log('=== PLAN 2 METADATA ===\n');
  console.log(JSON.stringify(plan2, null, 2));

  // Check route dates
  const routes = await prisma.dvi_itinerary_routes.findMany({
    where: { itinerary_plan_id: 2 },
    select: {
      itinerary_route_ID: true,
      itinerary_route_date: true,
      location_name: true,
    },
  });
  
  console.log('\n=== PLAN 2 ROUTES ===\n');
  console.log(JSON.stringify(routes, null, 2));

  // Check if hotels are all close by distance or spread across categories
  const hotels = await prisma.dvi_itinerary_plan_hotel_details.findMany({
    where: { itinerary_plan_id: 2 },
    select: {
      hotel_id: true,
      hotel_category_id: true,
      itinerary_route_location: true,
      group_type: true,
    },
  });

  console.log('\n=== PLAN 2 HOTELS ===\n');
  console.log(JSON.stringify(hotels, null, 2));

  // Get hotel coordinates to check if selection was by distance
  const hotelIds = [...new Set(hotels.map(h => h.hotel_id))];
  const hotelDetails = await prisma.dvi_hotel.findMany({
    where: { hotel_id: { in: hotelIds } },
    select: {
      hotel_id: true,
      hotel_name: true,
      hotel_category: true,
      hotel_city: true,
      hotel_latitude: true,
      hotel_longitude: true,
    },
  });

  console.log('\n=== HOTEL COORDINATES ===\n');
  console.log(JSON.stringify(hotelDetails, null, 2));

  await prisma.$disconnect();
}

tracePlan2().catch(console.error);
