const { PrismaClient } = require('@prisma/client');
const prisma = new PrismaClient();

async function checkRoute179Via() {
  const viaRoutes = await prisma.dvi_itinerary_via_route_details.findMany({
    where: { itinerary_route_ID: 179, deleted: 0 },
    select: {
      itinerary_via_location_ID: true,
      itinerary_via_location_name: true
    }
  });

  console.log('Route 179 via routes:');
  console.log('====================\n');
  console.log(JSON.stringify(viaRoutes, null, 2));

  await prisma.$disconnect();
}

checkRoute179Via().catch(console.error);
