const { PrismaClient } = require('@prisma/client');
const prisma = new PrismaClient();

async function checkDay1() {
  try {
    const route = await prisma.dvi_itinerary_route_details.findFirst({
      where: { itinerary_plan_ID: 13, deleted: 0, itinerary_route_ID: 1285 },
    });

    if (!route) {
      console.log('No route found');
      return;
    }

    console.log(`\n=== Route ${route.itinerary_route_ID} (Day 1) ===`);
    console.log(`${route.itinerary_route_starting_location} → ${route.itinerary_route_destination_location}`);
    console.log(`Start: ${route.itinerary_route_start_time}, End: ${route.itinerary_route_end_time}`);

    const items = await prisma.dvi_itinerary_route_hotspot_details.findMany({
      where: {
        itinerary_plan_ID: 13,
        itinerary_route_ID: route.itinerary_route_ID,
        deleted: 0,
      },
      orderBy: { hotspot_order: 'asc' },
    });

    console.log(`\nTotal items: ${items.length}\n`);
    
    for (const item of items) {
      const typeLabel = item.item_type === 1 ? 'SIGHTSEEING' : item.item_type === 3 ? 'TRAVEL' : item.item_type === 2 ? 'BREAK' : 'UNKNOWN';
      console.log(`${item.hotspot_order}. ${typeLabel} - Hotspot ID: ${item.hotspot_ID}, Times: ${item.hotspot_start_time} - ${item.hotspot_end_time}`);
    }

    const sightseeingCount = items.filter(i => i.item_type === 1).length;
    console.log(`\n=> ${sightseeingCount} sightseeing hotspots on Day 1`);

    await prisma.$disconnect();
  } catch (error) {
    console.error('Error:', error);
    await prisma.$disconnect();
  }
}

checkDay1();
