const { PrismaClient } = require('@prisma/client');
const prisma = new PrismaClient();

async function findPlan2Routes() {
  const routes = await prisma.dvi_itinerary_route_details.findMany({
    where: { itinerary_plan_ID: 2, deleted: 0 },
    select: {
      itinerary_route_ID: true,
      itinerary_route_date: true,
      location_name: true,
      next_visiting_location: true
    },
    orderBy: { itinerary_route_date: 'asc' }
  });

  console.log('Plan 2 Routes:');
  console.table(routes);

  await prisma.$disconnect();
}

findPlan2Routes();
