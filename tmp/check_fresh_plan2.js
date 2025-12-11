const { PrismaClient } = require('@prisma/client');
const prisma = new PrismaClient();

async function main() {
  console.log('\n=== PLAN 2 ROUTES (FRESH DATA) ===\n');

  // Get all routes for Plan 2
  const routes = await prisma.dvi_itinerary_route_details.findMany({
    where: {
      itinerary_plan_ID: 2,
      deleted: 0
    },
    select: {
      itinerary_route_ID: true,
      location_name: true,
      itinerary_route_date: true,
      route_start_time: true,
      route_end_time: true,
      createdon: true
    },
    orderBy: { itinerary_route_ID: 'asc' }
  });

  routes.forEach((r, idx) => {
    console.log(`Route ${idx + 1}: ID ${r.itinerary_route_ID}`);
    console.log(`  Location: ${r.location_name}`);
    console.log(`  Date: ${r.itinerary_route_date.toISOString().substr(0, 10)}`);
    console.log(`  Time: ${r.route_start_time?.toISOString().substr(11, 8)} - ${r.route_end_time?.toISOString().substr(11, 8)}`);
    console.log(`  Created: ${r.createdon.toISOString()}`);
    console.log('');
  });

  // Get the 2nd route (as specified in the SQL query)
  const route2 = routes[1]; // 2nd route (0-indexed)
  
  if (!route2) {
    console.log('❌ No 2nd route found for Plan 2!');
    return;
  }

  console.log(`\n=== ROUTE ${route2.itinerary_route_ID} HOTSPOTS (2nd Route) ===\n`);

  const hotspots = await prisma.dvi_itinerary_route_hotspot_details.findMany({
    where: {
      itinerary_route_ID: route2.itinerary_route_ID,
      deleted: 0
    },
    select: {
      route_hotspot_ID: true,
      hotspot_ID: true,
      item_type: true,
      hotspot_order: true,
      hotspot_travelling_distance: true,
      hotspot_traveling_time: true,
      hotspot_start_time: true,
      hotspot_end_time: true
    },
    orderBy: { hotspot_order: 'asc' }
  });

  console.log(`Total hotspot records: ${hotspots.length}\n`);

  const itemTypes = {
    1: 'VISIT',
    2: 'BREAK',
    3: 'TRAVEL',
    4: 'VISIT',
    5: 'WAIT',
    6: 'LUNCH',
    7: 'DINNER'
  };

  let lastVisitHotspot = 0;
  const visitSequence = [];

  hotspots.forEach(h => {
    const type = itemTypes[h.item_type] || `TYPE-${h.item_type}`;
    const mins = h.hotspot_traveling_time ? 
      (h.hotspot_traveling_time.getUTCHours() * 60 + h.hotspot_traveling_time.getUTCMinutes()) : 0;
    
    if ([1, 4].includes(h.item_type)) {
      visitSequence.push(h.hotspot_ID);
      lastVisitHotspot = h.hotspot_ID;
      console.log(`Order ${h.hotspot_order}: H${h.hotspot_ID} (${type}) - ${h.hotspot_start_time?.toISOString().substr(11, 8)} to ${h.hotspot_end_time?.toISOString().substr(11, 8)}`);
    } else if (h.item_type === 3) {
      console.log(`Order ${h.hotspot_order}: → H${h.hotspot_ID} (${type}) - ${h.hotspot_travelling_distance} km, ${mins} min`);
    } else {
      console.log(`Order ${h.hotspot_order}: H${h.hotspot_ID} (${type}) - ${h.hotspot_start_time?.toISOString().substr(11, 8)} to ${h.hotspot_end_time?.toISOString().substr(11, 8)}`);
    }
  });

  console.log(`\n=== VISIT SEQUENCE ===`);
  console.log(`Hotspots visited: [${visitSequence.join(', ')}]`);
  console.log(`Total visits: ${visitSequence.length}`);

  // Check if hotspot 18 is present
  if (visitSequence.includes(18)) {
    console.log('\n✅ Hotspot 18 IS present in the route');
  } else {
    console.log('\n❌ Hotspot 18 is MISSING from the route');
  }

  // Expected sequence for comparison
  const expectedPHP = [4, 18, 21, 19, 17, 678];
  console.log(`\nExpected (PHP Route 2): [${expectedPHP.join(', ')}]`);
  
  const matches = visitSequence.length === expectedPHP.length && 
                  visitSequence.every((val, idx) => val === expectedPHP[idx]);
  
  if (matches) {
    console.log('✅ PERFECT MATCH!');
  } else {
    console.log('❌ MISMATCH - Different hotspot sequence');
  }

  // Get travel time to hotspot 4 if exists
  const h4Travel = hotspots.find(h => h.hotspot_ID === 4 && h.item_type === 3);
  if (h4Travel) {
    const h4mins = h4Travel.hotspot_traveling_time.getUTCHours() * 60 + 
                   h4Travel.hotspot_traveling_time.getUTCMinutes();
    console.log(`\n=== HOTSPOT 4 TRAVEL DETAILS ===`);
    console.log(`Distance: ${h4Travel.hotspot_travelling_distance} km`);
    console.log(`Travel time: ${h4mins} minutes (${h4Travel.hotspot_traveling_time.toISOString().substr(11, 8)})`);
    
    if (h4mins === 34) {
      console.log('⚠️  Still showing 34 minutes (PHP bug persists)');
    } else if (h4mins === 13) {
      console.log('✅ Showing 13 minutes (correct calculation!)');
    }
  }
}

main()
  .catch(console.error)
  .finally(() => prisma.$disconnect());
