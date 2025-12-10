const { PrismaClient } = require('@prisma/client');
const prisma = new PrismaClient();

async function compareRoute2Details() {
  console.log('=== COMPARING ROUTE 2 DETAILS FOR PLAN 2 vs PLAN 5 ===\n');
  
  // Get 2nd route for each plan
  const plan2Routes = await prisma.dvi_itinerary_route_details.findMany({
    where: { itinerary_plan_ID: 2, deleted: 0, status: 1 },
    orderBy: { itinerary_route_ID: 'asc' },
    select: { itinerary_route_ID: true }
  });
  
  const plan5Routes = await prisma.dvi_itinerary_route_details.findMany({
    where: { itinerary_plan_ID: 5, deleted: 0, status: 1 },
    orderBy: { itinerary_route_ID: 'asc' },
    select: { itinerary_route_ID: true }
  });
  
  const route2Plan2 = plan2Routes[1]?.itinerary_route_ID;
  const route2Plan5 = plan5Routes[1]?.itinerary_route_ID;
  
  console.log(`Plan 2 Route 2 ID: ${route2Plan2}`);
  console.log(`Plan 5 Route 2 ID: ${route2Plan5}\n`);
  
  // Get all hotspot details for both routes
  const plan2Details = await prisma.dvi_itinerary_route_hotspot_details.findMany({
    where: { 
      itinerary_plan_ID: 2, 
      itinerary_route_ID: route2Plan2,
      deleted: 0,
      status: 1
    },
    orderBy: { hotspot_order: 'asc' }
  });
  
  const plan5Details = await prisma.dvi_itinerary_route_hotspot_details.findMany({
    where: { 
      itinerary_plan_ID: 5, 
      itinerary_route_ID: route2Plan5,
      deleted: 0,
      status: 1
    },
    orderBy: { hotspot_order: 'asc' }
  });
  
  console.log(`Plan 2 Route 2: ${plan2Details.length} rows`);
  console.log(`Plan 5 Route 2: ${plan5Details.length} rows\n`);
  
  // Compare row counts by item_type
  const p2ByType = {};
  const p5ByType = {};
  
  plan2Details.forEach(r => {
    if (!p2ByType[r.item_type]) p2ByType[r.item_type] = [];
    p2ByType[r.item_type].push(r);
  });
  
  plan5Details.forEach(r => {
    if (!p5ByType[r.item_type]) p5ByType[r.item_type] = [];
    p5ByType[r.item_type].push(r);
  });
  
  const typeNames = {1: 'Refreshment', 3: 'Travel', 4: 'Stay', 5: 'Parking', 6: 'Hotel'};
  
  console.log('ROW COUNT BY ITEM_TYPE:');
  console.log('━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━');
  Object.keys({...p2ByType, ...p5ByType}).sort().forEach(type => {
    const p2Count = (p2ByType[type] || []).length;
    const p5Count = (p5ByType[type] || []).length;
    const match = p2Count === p5Count ? '✅' : '❌';
    console.log(`Type ${type} (${typeNames[type]}): Plan2=${p2Count}, Plan5=${p5Count} ${match}`);
  });
  
  console.log('\n\nHOTSPOT IDs COMPARISON:');
  console.log('━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━');
  
  const p2Hotspots = plan2Details.filter(r => r.hotspot_ID > 0).map(r => r.hotspot_ID);
  const p5Hotspots = plan5Details.filter(r => r.hotspot_ID > 0).map(r => r.hotspot_ID);
  
  console.log(`Plan 2: [${p2Hotspots.join(', ')}]`);
  console.log(`Plan 5: [${p5Hotspots.join(', ')}]`);
  
  const p2Set = new Set(p2Hotspots);
  const p5Set = new Set(p5Hotspots);
  
  const inBoth = p2Hotspots.filter(id => p5Set.has(id));
  const onlyP2 = p2Hotspots.filter(id => !p5Set.has(id));
  const onlyP5 = p5Hotspots.filter(id => !p2Set.has(id));
  
  console.log(`\nMatching: [${[...new Set(inBoth)].join(', ')}] (${inBoth.length}/${p2Hotspots.length})`);
  if (onlyP2.length) console.log(`Only in Plan 2: [${onlyP2.join(', ')}]`);
  if (onlyP5.length) console.log(`Only in Plan 5: [${onlyP5.join(', ')}]`);
  
  // Detailed field comparison for matching item_type
  console.log('\n\nDETAILED FIELD COMPARISON:');
  console.log('━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n');
  
  for (let i = 0; i < Math.max(plan2Details.length, plan5Details.length); i++) {
    const p2 = plan2Details[i];
    const p5 = plan5Details[i];
    
    if (!p2 && !p5) continue;
    
    console.log(`Row ${i + 1}:`);
    if (!p2) {
      console.log(`  Plan 2: MISSING`);
      console.log(`  Plan 5: item_type=${p5.item_type}, hotspot_ID=${p5.hotspot_ID || 0}, order=${p5.hotspot_order}`);
    } else if (!p5) {
      console.log(`  Plan 2: item_type=${p2.item_type}, hotspot_ID=${p2.hotspot_ID || 0}, order=${p2.hotspot_order}`);
      console.log(`  Plan 5: MISSING`);
    } else {
      const typeMatch = p2.item_type === p5.item_type ? '✅' : '❌';
      const hotspotMatch = p2.hotspot_ID === p5.hotspot_ID ? '✅' : '❌';
      const orderMatch = p2.hotspot_order === p5.hotspot_order ? '✅' : '❌';
      
      console.log(`  Item Type: ${p2.item_type} vs ${p5.item_type} ${typeMatch}`);
      console.log(`  Hotspot ID: ${p2.hotspot_ID || 0} vs ${p5.hotspot_ID || 0} ${hotspotMatch}`);
      console.log(`  Order: ${p2.hotspot_order} vs ${p5.hotspot_order} ${orderMatch}`);
      
      if (p2.item_type !== p5.item_type || p2.hotspot_ID !== p5.hotspot_ID) {
        console.log(`  ⚠️ MISMATCH DETECTED`);
      }
    }
    console.log('');
  }
  
  await prisma.$disconnect();
}

compareRoute2Details().catch(console.error);
