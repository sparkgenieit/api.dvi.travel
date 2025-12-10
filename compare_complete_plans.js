const { PrismaClient } = require('@prisma/client');
const prisma = new PrismaClient();

async function compareCompletePlans() {
  console.log('=== COMPARING PLAN 2 (PHP) vs PLAN 5 (NESTJS) ===\n');
  
  // Get all routes for both plans
  const plan2Routes = await prisma.dvi_itinerary_route_details.findMany({
    where: { itinerary_plan_ID: 2, deleted: 0, status: 1 },
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
  
  const plan5Routes = await prisma.dvi_itinerary_route_details.findMany({
    where: { itinerary_plan_ID: 5, deleted: 0, status: 1 },
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
  
  console.log(`Plan 2 has ${plan2Routes.length} routes`);
  console.log(`Plan 5 has ${plan5Routes.length} routes\n`);
  
  // Compare each route
  for (let i = 0; i < Math.max(plan2Routes.length, plan5Routes.length); i++) {
    const p2Route = plan2Routes[i];
    const p5Route = plan5Routes[i];
    
    console.log(`\n━━━ ROUTE ${i + 1} ━━━`);
    
    if (p2Route) {
      console.log(`\nPHP Route ${p2Route.itinerary_route_ID}:`);
      console.log(`  ${p2Route.location_name} → ${p2Route.next_visiting_location}`);
      console.log(`  Time: ${p2Route.route_start_time} - ${p2Route.route_end_time}`);
      console.log(`  Direct: ${p2Route.direct_to_next_visiting_place}`);
      
      // Get hotspots for this route
      const p2Hotspots = await prisma.dvi_itinerary_route_hotspot_details.findMany({
        where: {
          itinerary_plan_ID: 2,
          itinerary_route_ID: p2Route.itinerary_route_ID,
          deleted: 0,
          status: 1
        },
        orderBy: { hotspot_order: 'asc' },
        select: {
          hotspot_order: true,
          item_type: true,
          hotspot_ID: true,
          hotspot_start_time: true,
          hotspot_end_time: true
        }
      });
      
      console.log(`  Total rows: ${p2Hotspots.length}`);
      
      // Group by item_type
      const itemTypes = {};
      p2Hotspots.forEach(h => {
        if (!itemTypes[h.item_type]) itemTypes[h.item_type] = [];
        itemTypes[h.item_type].push(h);
      });
      
      Object.keys(itemTypes).sort().forEach(type => {
        const typeMap = {1: 'Refreshment', 2: 'Direct Destination', 3: 'Travel', 4: 'Hotspot Stay', 5: 'Parking', 6: 'Hotel', 7: 'Checkout'};
        console.log(`  - item_type ${type} (${typeMap[type]}): ${itemTypes[type].length} rows`);
        if (type === '3' || type === '4') {
          const hotspotIds = [...new Set(itemTypes[type].filter(h => h.hotspot_ID > 0).map(h => h.hotspot_ID))];
          console.log(`    Hotspot IDs: ${hotspotIds.join(', ')}`);
        }
      });
    }
    
    if (p5Route) {
      console.log(`\nNestJS Route ${p5Route.itinerary_route_ID}:`);
      console.log(`  ${p5Route.location_name} → ${p5Route.next_visiting_location}`);
      console.log(`  Time: ${p5Route.route_start_time} - ${p5Route.route_end_time}`);
      console.log(`  Direct: ${p5Route.direct_to_next_visiting_place}`);
      
      // Get hotspots for this route
      const p5Hotspots = await prisma.dvi_itinerary_route_hotspot_details.findMany({
        where: {
          itinerary_plan_ID: 5,
          itinerary_route_ID: p5Route.itinerary_route_ID,
          deleted: 0,
          status: 1
        },
        orderBy: { hotspot_order: 'asc' },
        select: {
          hotspot_order: true,
          item_type: true,
          hotspot_ID: true,
          hotspot_start_time: true,
          hotspot_end_time: true
        }
      });
      
      console.log(`  Total rows: ${p5Hotspots.length}`);
      
      // Group by item_type
      const itemTypes = {};
      p5Hotspots.forEach(h => {
        if (!itemTypes[h.item_type]) itemTypes[h.item_type] = [];
        itemTypes[h.item_type].push(h);
      });
      
      Object.keys(itemTypes).sort().forEach(type => {
        const typeMap = {1: 'Refreshment', 2: 'Direct Destination', 3: 'Travel', 4: 'Hotspot Stay', 5: 'Parking', 6: 'Hotel', 7: 'Checkout'};
        console.log(`  - item_type ${type} (${typeMap[type]}): ${itemTypes[type].length} rows`);
        if (type === '3' || type === '4') {
          const hotspotIds = [...new Set(itemTypes[type].filter(h => h.hotspot_ID > 0).map(h => h.hotspot_ID))];
          console.log(`    Hotspot IDs: ${hotspotIds.join(', ')}`);
        }
      });
    }
    
    // Show match status
    if (p2Route && p5Route) {
      const p2HotspotIds = [...new Set((await prisma.dvi_itinerary_route_hotspot_details.findMany({
        where: { itinerary_plan_ID: 2, itinerary_route_ID: p2Route.itinerary_route_ID, deleted: 0, status: 1, item_type: { in: [3, 4] } }
      })).map(h => h.hotspot_ID))].sort();
      
      const p5HotspotIds = [...new Set((await prisma.dvi_itinerary_route_hotspot_details.findMany({
        where: { itinerary_plan_ID: 5, itinerary_route_ID: p5Route.itinerary_route_ID, deleted: 0, status: 1, item_type: { in: [3, 4] } }
      })).map(h => h.hotspot_ID))].sort();
      
      if (JSON.stringify(p2HotspotIds) === JSON.stringify(p5HotspotIds)) {
        console.log('\n  ✅ HOTSPOTS MATCH');
      } else {
        console.log('\n  ❌ HOTSPOTS MISMATCH');
        console.log(`     PHP has: ${p2HotspotIds.join(', ')}`);
        console.log(`     NestJS has: ${p5HotspotIds.join(', ')}`);
      }
    }
  }
  
  await prisma.$disconnect();
}

compareCompletePlans();
