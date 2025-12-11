const { PrismaClient } = require('@prisma/client');
const prisma = new PrismaClient();

(async () => {
  const routes = await prisma.dvi_itinerary_route_details.findMany({
    where: { itinerary_plan_ID: 5 },
    select: {
      itinerary_route_ID: true,
      itinerary_route_date: true,
      location_name: true
    }
  });
  
  console.log('Plan 5 routes:');
  console.log(JSON.stringify(routes, null, 2));
  
  await prisma.$disconnect();
})();
