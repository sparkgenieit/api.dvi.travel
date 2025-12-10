const { PrismaClient } = require('@prisma/client');
const prisma = new PrismaClient();

async function main() {
  const routes = await prisma.dvi_itinerary_route_details.findMany({
    where: {
      itinerary_plan_ID: 5,
    },
    orderBy: {
      itinerary_route_ID: 'asc',
    },
  });

  console.log('\n=== All Routes for Plan 5 ===');
  for (const route of routes) {
    const hotspots = await prisma.dvi_itinerary_route_hotspot_details.findMany({
      where: {
        itinerary_plan_ID: 5,
        itinerary_route_ID: route.itinerary_route_ID,
      },
      orderBy: {
        hotspot_order: 'asc',
      },
    });

    const hotspotIds = hotspots
      .filter(h => h.item_type === 4)
      .map(h => h.hotspot_ID);

    console.log(`\nRoute ${route.itinerary_route_ID}: ${route.itinerary_route_date} (${hotspots.length} items)`);
    console.log(`  Hotspots: [${hotspotIds.join(', ')}]`);
    
    // Show first few items
    hotspots.slice(0, 8).forEach((item) => {
      console.log(`  Order ${item.hotspot_order}: type=${item.item_type}, hotspot=${item.hotspot_ID}, ${item.hotspot_start_time?.toISOString().substring(11, 19)} - ${item.hotspot_end_time?.toISOString().substring(11, 19)}, break=${item.allow_break_hours}`);
    });
  }

  await prisma.$disconnect();
}

main();
