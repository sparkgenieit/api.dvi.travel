const { PrismaClient } = require('@prisma/client');
const prisma = new PrismaClient();

async function checkRoute1() {
  try {
    // Get Plan 2 routes
    const routes = await prisma.$queryRaw`
      SELECT itinerary_route_ID, location_name, next_visiting_location
      FROM dvi_itinerary_route_details 
      WHERE itinerary_plan_ID = 2 AND deleted = 0
      ORDER BY itinerary_route_date, itinerary_route_ID
    `;

    const route1Id = routes[0].itinerary_route_ID;
    const route2Id = routes[1].itinerary_route_ID;

    console.log(`\nRoute 1 ID: ${route1Id} (${routes[0].location_name} → ${routes[0].next_visiting_location})`);
    console.log(`Route 2 ID: ${route2Id} (${routes[1].location_name} → ${routes[1].next_visiting_location})`);

    // Get Plan 2 Route 1 hotspots
    const route1 = await prisma.$queryRaw`
      SELECT route_hotspot_ID, itinerary_route_ID, item_type, hotspot_ID, hotspot_order
      FROM dvi_itinerary_route_hotspot_details
      WHERE itinerary_route_ID = ${route1Id}
      AND item_type = 4
      AND deleted = 0
      ORDER BY hotspot_order
    `;

    console.log('\n=== ROUTE 1 HOTSPOTS ===');
    console.log(route1.map(r => r.hotspot_ID));

    // Get Plan 2 Route 2 hotspots
    const route2 = await prisma.$queryRaw`
      SELECT route_hotspot_ID, itinerary_route_ID, item_type, hotspot_ID, hotspot_order
      FROM dvi_itinerary_route_hotspot_details
      WHERE itinerary_route_ID = ${route2Id}
      AND item_type = 4
      AND deleted = 0
      ORDER BY hotspot_order
    `;

    console.log('\n=== ROUTE 2 HOTSPOTS ===');
    console.log(route2.map(r => r.hotspot_ID));

    const route1Ids = route1.map(r => Number(r.hotspot_ID));
    const route2Ids = route2.map(r => Number(r.hotspot_ID));
    
    const overlap = route1Ids.filter(id => route2Ids.includes(id));
    
    console.log('\n=== ANALYSIS ===\n');
    if (overlap.length > 0) {
      console.log(`✅ PHP ALLOWS HOTSPOT REUSE! Shared: [${overlap.join(', ')}]`);
      console.log('\nNestJS duplicate prevention is BLOCKING Route 2 from using hotspot 4!');
    } else {
      console.log('❌ No shared hotspots - PHP also prevents reuse');
    }

    console.log('\nHotspot 4 in Route 1?', route1Ids.includes(4) ? '✅ YES' : '❌ NO');
    console.log('Hotspot 4 in Route 2?', route2Ids.includes(4) ? '✅ YES' : '❌ NO');

  } finally {
    await prisma.$disconnect();
  }
}

checkRoute1().catch(console.error);
