const { PrismaClient } = require('@prisma/client');
const prisma = new PrismaClient();

async function main() {
  const routes = await prisma.dvi_itinerary_route_details.findMany({
    where: { itinerary_plan_ID: 5 },
    orderBy: { itinerary_route_ID: 'asc' },
  });

  console.log('\n=== Plan 5 Routes (After Baseline) ===');
  for (const route of routes) {
    const hotspots = await prisma.dvi_itinerary_route_hotspot_details.findMany({
      where: {
        itinerary_plan_ID: 5,
        itinerary_route_ID: route.itinerary_route_ID,
      },
      orderBy: { hotspot_order: 'asc' },
    });

    const hotspotIds = hotspots
      .filter(h => h.item_type === 4)
      .map(h => h.hotspot_ID);

    console.log(`\nRoute ${route.itinerary_route_ID}: ${route.itinerary_route_date?.toISOString().substring(0, 10)}`);
    console.log(`  Start: ${route.route_start_time?.toISOString().substring(11, 19)}, End: ${route.route_end_time?.toISOString().substring(11, 19)}`);
    console.log(`  Hotspots: [${hotspotIds.join(', ')}]`);
  }

  // Compare with PHP Plan 2
  console.log('\n=== PHP Plan 2 Expected ===');
  console.log('Route 1 (179): [5]');
  console.log('Route 2 (179): [4, 18, 21, 19, 17, 678]');

  await prisma.$disconnect();
}

main();
