const { PrismaClient } = require('@prisma/client');
const prisma = new PrismaClient();

async function checkHotspots() {
  try {
    const routes = await prisma.dvi_itinerary_route_details.findMany({
      where: { itinerary_plan_ID: 13, deleted: 0 },
      orderBy: { itinerary_route_ID: 'desc' },
      take: 5,
    });

    console.log('\n=== Latest Routes for Plan 13 ===');
    for (const route of routes) {
      const hotspots = await prisma.dvi_itinerary_route_hotspot_details.findMany({
        where: {
          itinerary_plan_ID: 13,
          itinerary_route_ID: route.itinerary_route_ID,
          deleted: 0,
        },
      });

      console.log(`\nRoute ${route.itinerary_route_ID}: ${hotspots.length} hotspots`);
      console.log(`  ${route.itinerary_route_starting_location} → ${route.itinerary_route_destination_location}`);
      
      hotspots.forEach(h => {
        console.log(`  - ${h.item_type === 1 ? 'Hotspot' : h.item_type === 3 ? 'Travel' : 'Other'} #${h.hotspot_ID} (order: ${h.item_order})`);
      });
    }

    await prisma.$disconnect();
  } catch (error) {
    console.error('Error:', error);
    await prisma.$disconnect();
  }
}

checkHotspots();
