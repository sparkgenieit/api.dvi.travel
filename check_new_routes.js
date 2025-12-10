const { PrismaClient } = require('@prisma/client');
const prisma = new PrismaClient();

async function checkRoutes() {
  const routes = await prisma.dvi_itinerary_route_details.findMany({
    where: {
      itinerary_route_ID: { in: [346, 347, 348] },
      deleted: 0
    },
    select: {
      itinerary_route_ID: true,
      location_name: true,
      next_visiting_location: true,
      route_start_time: true,
      route_end_time: true,
      direct_to_next_visiting_place: true
    },
    orderBy: { itinerary_route_ID: 'asc' }
  });

  console.log('NestJS Routes 346-348:');
  console.log('======================\n');
  routes.forEach(r => {
    console.log(`Route ${r.itinerary_route_ID}: ${r.location_name} → ${r.next_visiting_location}`);
    console.log(`  Time: ${r.route_start_time} - ${r.route_end_time}`);
    console.log(`  Direct: ${r.direct_to_next_visiting_place}`);
    console.log();
  });

  await prisma.$disconnect();
}

checkRoutes().catch(console.error);
