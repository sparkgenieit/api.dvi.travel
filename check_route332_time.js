const { PrismaClient } = require('@prisma/client');
const prisma = new PrismaClient();

async function checkRoute() {
  const route = await prisma.dvi_itinerary_route_details.findUnique({
    where: { itinerary_route_ID: 332 },
    select: {
      route_start_time: true,
      route_end_time: true,
      itinerary_route_date: true
    }
  });

  console.log('Route 332 times:');
  console.log(route);

  await prisma.$disconnect();
}

checkRoute().catch(console.error);
