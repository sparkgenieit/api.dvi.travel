const { PrismaClient } = require('@prisma/client');
const prisma = new PrismaClient();

async function main() {
  const routes = await prisma.dvi_itinerary_route_details.findMany({
    where: { itinerary_plan_ID: 5 },
    orderBy: { itinerary_route_ID: 'desc' },
    take: 3,
  });

  console.log('\n=== Plan 5 Latest Routes ===');
  for (const route of routes.reverse()) {
    const hotspots = await prisma.dvi_itinerary_route_hotspot_details.findMany({
      where: {
        itinerary_plan_ID: 5,
        itinerary_route_ID: route.itinerary_route_ID,
        item_type: 4,
      },
      orderBy: { hotspot_order: 'asc' },
    });

    const hotspotIds = hotspots.map(h => h.hotspot_ID);

    console.log(`\nRoute ${route.itinerary_route_ID}:`);
    console.log(`  Hotspots: [${hotspotIds.join(', ')}]`);
    
    // Show first few items with times
    const allItems = await prisma.dvi_itinerary_route_hotspot_details.findMany({
      where: {
        itinerary_plan_ID: 5,
        itinerary_route_ID: route.itinerary_route_ID,
      },
      orderBy: { hotspot_order: 'asc' },
      take: 10,
    });
    
    allItems.forEach(item => {
      const start = item.hotspot_start_time?.toISOString().substring(11, 19);
      const end = item.hotspot_end_time?.toISOString().substring(11, 19);
      const types = {1: 'Break', 3: 'Travel', 4: 'Visit'};
      console.log(`    ${item.hotspot_order}. ${types[item.item_type] || item.item_type} h${item.hotspot_ID} ${start}-${end}`);
    });
  }

  console.log('\n=== PHP Plan 2 Expected ===');
  console.log('Route 1: [5]');
  console.log('Route 2: [4, 18, 21, 19, 17, 678]');

  await prisma.$disconnect();
}

main();
