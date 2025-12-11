const { PrismaClient } = require('@prisma/client');
const prisma = new PrismaClient();

async function analyzeHotspotSelection() {
  console.log('\n=== ANALYZING HOTSPOT SELECTION FOR PLAN 2 VS PLAN 5 ===\n');
  
  // Get routes for both plans
  const plan2Routes = await prisma.dvi_itinerary_route_details.findMany({
    where: { itinerary_plan_ID: 2, deleted: 0 },
    orderBy: { itinerary_route_date: 'asc' }
  });
  
  const plan5Routes = await prisma.dvi_itinerary_route_details.findMany({
    where: { itinerary_plan_ID: 5, deleted: 0 },
    orderBy: { itinerary_route_date: 'asc' }
  });
  
  console.log(`Plan 2 has ${plan2Routes.length} routes`);
  console.log(`Plan 5 has ${plan5Routes.length} routes\n`);
  
  // For each route, analyze the hotspot sequence
  for (let i = 0; i < Math.max(plan2Routes.length, plan5Routes.length); i++) {
    const r2 = plan2Routes[i];
    const r5 = plan5Routes[i];
    
    console.log(`\n=== ROUTE ${i + 1} ===`);
    
    if (r2) {
      console.log(`Plan 2 Route ID: ${r2.itinerary_route_ID}`);
      console.log(`  Location: ${r2.location_name} → ${r2.next_visiting_location}`);
      console.log(`  Direct: ${r2.direct_to_next_visiting_place}`);
      
      const hotspots2 = await prisma.dvi_itinerary_route_hotspot_details.findMany({
        where: {
          itinerary_plan_ID: 2,
          itinerary_route_ID: r2.itinerary_route_ID,
          deleted: 0
        },
        orderBy: { hotspot_order: 'asc' }
      });
      
      console.log(`  ${hotspots2.length} hotspot detail rows:`);
      hotspots2.forEach(h => {
        const typeName = {1: 'Refresh', 3: 'Travel', 4: 'Visit', 5: 'ToHotel', 6: 'AtHotel', 7: 'Return'}[h.item_type] || 'Unknown';
        if (h.item_type === 4 || h.item_type === 3) {
          console.log(`    Order ${h.hotspot_order}: Type ${h.item_type} (${typeName}) - Hotspot ID: ${h.hotspot_ID}`);
        } else {
          console.log(`    Order ${h.hotspot_order}: Type ${h.item_type} (${typeName})`);
        }
      });
    }
    
    if (r5) {
      console.log(`\nPlan 5 Route ID: ${r5.itinerary_route_ID}`);
      console.log(`  Location: ${r5.location_name} → ${r5.next_visiting_location}`);
      console.log(`  Direct: ${r5.direct_to_next_visiting_place}`);
      
      const hotspots5 = await prisma.dvi_itinerary_route_hotspot_details.findMany({
        where: {
          itinerary_plan_ID: 5,
          itinerary_route_ID: r5.itinerary_route_ID,
          deleted: 0
        },
        orderBy: { hotspot_order: 'asc' }
      });
      
      console.log(`  ${hotspots5.length} hotspot detail rows:`);
      hotspots5.forEach(h => {
        const typeName = {1: 'Refresh', 3: 'Travel', 4: 'Visit', 5: 'ToHotel', 6: 'AtHotel', 7: 'Return'}[h.item_type] || 'Unknown';
        if (h.item_type === 4 || h.item_type === 3) {
          console.log(`    Order ${h.hotspot_order}: Type ${h.item_type} (${typeName}) - Hotspot ID: ${h.hotspot_ID}`);
        } else {
          console.log(`    Order ${h.hotspot_order}: Type ${h.item_type} (${typeName})`);
        }
      });
    }
    
    // Compare hotspot selection
    if (r2 && r5) {
      const hotspots2IDs = await prisma.dvi_itinerary_route_hotspot_details.findMany({
        where: {
          itinerary_plan_ID: 2,
          itinerary_route_ID: r2.itinerary_route_ID,
          item_type: 4, // Only visit rows
          deleted: 0
        },
        select: { hotspot_ID: true },
        orderBy: { hotspot_order: 'asc' }
      });
      
      const hotspots5IDs = await prisma.dvi_itinerary_route_hotspot_details.findMany({
        where: {
          itinerary_plan_ID: 5,
          itinerary_route_ID: r5.itinerary_route_ID,
          item_type: 4, // Only visit rows
          deleted: 0
        },
        select: { hotspot_ID: true },
        orderBy: { hotspot_order: 'asc' }
      });
      
      const ids2 = hotspots2IDs.map(h => h.hotspot_ID);
      const ids5 = hotspots5IDs.map(h => h.hotspot_ID);
      
      console.log(`\n  Hotspot Visit Comparison:`);
      console.log(`    Plan 2 visited: [${ids2.join(', ')}]`);
      console.log(`    Plan 5 visited: [${ids5.join(', ')}]`);
      
      if (JSON.stringify(ids2) !== JSON.stringify(ids5)) {
        console.log(`    ⚠️  MISMATCH IN HOTSPOT SELECTION!`);
      } else {
        console.log(`    ✅ Same hotspots`);
      }
    }
  }
  
  await prisma.$disconnect();
}

analyzeHotspotSelection();
