const { PrismaClient } = require('@prisma/client');
const prisma = new PrismaClient();

async function checkNestJSRoute1() {
  try {
    // Get Plan 5 routes
    const routes = await prisma.$queryRaw`
      SELECT itinerary_route_ID, location_name, next_visiting_location
      FROM dvi_itinerary_route_details 
      WHERE itinerary_plan_ID = 5 AND deleted = 0
      ORDER BY itinerary_route_date, itinerary_route_ID
    `;

    const route1Id = routes[0].itinerary_route_ID;
    const route2Id = routes[1].itinerary_route_ID;

    console.log('\n=== PLAN 5 ROUTES ===');
    console.log(`Route 1 ID: ${route1Id} (${routes[0].location_name} → ${routes[0].next_visiting_location})`);
    console.log(`Route 2 ID: ${route2Id} (${routes[1].location_name} → ${routes[1].next_visiting_location})`);

    // Get Route 1 hotspots
    const route1 = await prisma.$queryRaw`
      SELECT route_hotspot_ID, itinerary_route_ID, item_type, hotspot_ID, hotspot_order
      FROM dvi_itinerary_route_hotspot_details
      WHERE itinerary_route_ID = ${route1Id}
      AND item_type = 4
      AND deleted = 0
      ORDER BY hotspot_order
    `;

    console.log('\n=== ROUTE 1 HOTSPOTS (NestJS) ===');
    const route1Ids = route1.map(r => r.hotspot_ID);
    console.log(route1Ids);

    // Get Route 2 hotspots  
    const route2 = await prisma.$queryRaw`
      SELECT route_hotspot_ID, itinerary_route_ID, item_type, hotspot_ID, hotspot_order
      FROM dvi_itinerary_route_hotspot_details
      WHERE itinerary_route_ID = ${route2Id}
      AND item_type = 4
      AND deleted = 0
      ORDER BY hotspot_order
    `;

    console.log('\n=== ROUTE 2 HOTSPOTS (NestJS) ===');
    const route2Ids = route2.map(r => r.hotspot_ID);
    console.log(route2Ids);

    console.log('\n=== COMPARISON ===\n');
    console.log('PHP Route 1: [5]');
    console.log(`NestJS Route 1: [${route1Ids.join(', ')}]`);
    
    if (route1Ids.includes(4)) {
      console.log('\n❌ PROBLEM: NestJS Route 1 uses hotspot 4');
      console.log('   PHP Route 1 uses hotspot 5');
      console.log('   Hotspot 4 should be in Route 2, not Route 1!');
    }

    if (route1Ids.includes(5)) {
      console.log('\n✅ CORRECT: NestJS Route 1 uses hotspot 5 (like PHP)');
    } else {
      console.log('\n❌ PROBLEM: NestJS Route 1 should use hotspot 5');
    }

    console.log('\n=== ROOT CAUSE ===');
    console.log('Route 1 (Chennai → Chennai) should select Marina Beach (ID 5)');
    console.log('Route 2 (Chennai → Pondicherry) should select Kapaleeshwarar Temple (ID 4)');
    console.log('NestJS is selecting them in the wrong routes!');

  } finally {
    await prisma.$disconnect();
  }
}

checkNestJSRoute1().catch(console.error);
