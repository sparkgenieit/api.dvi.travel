const { PrismaClient } = require('@prisma/client');
const prisma = new PrismaClient();

async function checkPlan2Routes() {
  const routes = await prisma.dvi_itinerary_route_details.findMany({
    where: { itinerary_plan_ID: 2 },
    select: {
      itinerary_route_ID: true,
      itinerary_route_date: true,
      location_name: true,
    }
  });

  console.log('Plan 2 Routes:');
  console.log(JSON.stringify(routes, null, 2));

  // Check hotels for each route
  for (const route of routes) {
    const hotels = await prisma.dvi_itinerary_plan_hotel_details.findMany({
      where: { 
        itinerary_plan_id: 2,
        itinerary_route_id: route.itinerary_route_ID
      },
      select: {
        group_type: true,
        hotel_id: true,
      }
    });
    console.log(`\nRoute ${route.itinerary_route_ID} (${route.location_name}): ${hotels.length} hotels`);
    if (hotels.length === 0) {
      console.log('  ⚠️ NO HOTELS for this route!');
    }
  }

  await prisma.$disconnect();
}

checkPlan2Routes().catch(console.error);
