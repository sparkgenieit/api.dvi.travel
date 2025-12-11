const { PrismaClient } = require('@prisma/client');
const prisma = new PrismaClient();

async function main() {
  const routes = await prisma.dvi_itinerary_route_details.findMany({
    where: {
      itinerary_plan_ID: 2,
      deleted: 0
    },
    select: {
      itinerary_route_ID: true,
      itinerary_plan_ID: true,
      location_name: true,
      itinerary_route_date: true,
      createdon: true
    },
    orderBy: { itinerary_route_ID: 'asc' }
  });

  console.log(`\nPlan 2 routes (${routes.length} found):\n`);
  routes.forEach(r => {
    console.log(`Route ${r.itinerary_route_ID}: ${r.location_name} - ${r.itinerary_route_date.toISOString().substr(0, 10)} (Created: ${r.createdon.toISOString()})`);
  });
}

main()
  .catch(console.error)
  .finally(() => prisma.$disconnect());
