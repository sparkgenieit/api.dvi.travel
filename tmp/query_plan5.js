const { PrismaClient } = require('@prisma/client');
const prisma = new PrismaClient();

async function queryPlan5() {
  const routes = await prisma.dvi_itinerary_route_details.findMany({
    where: { 
      itinerary_plan_ID: 5,
      deleted: 0,
      status: 1
    },
    orderBy: { itinerary_route_ID: 'asc' },
    select: {
      itinerary_route_ID: true,
      location_name: true,
      next_visiting_location: true,
      route_start_time: true,
      route_end_time: true,
      direct_to_next_visiting_place: true
    }
  });

  console.log('\n=== PLAN 5 ROUTES ===');
  console.table(routes);

  await prisma.$disconnect();
}

queryPlan5().catch(e => {
  console.error(e);
  process.exit(1);
});
