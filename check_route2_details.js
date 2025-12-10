const { PrismaClient } = require('@prisma/client');
const prisma = new PrismaClient();

async function compareRoute2() {
  console.log('=== COMPARING 2ND ROUTE FOR PLAN 2 VS PLAN 5 ===\n');
  
  // Get 2nd route for Plan 2
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
  
  if (plan2Routes.length < 2 || plan5Routes.length < 2) {
    console.log('Not enough routes in one or both plans');
    await prisma.$disconnect();
    return;
  }
  
  const route2Id = plan2Routes[1].itinerary_route_ID;
  const route5Id = plan5Routes[1].itinerary_route_ID;
  
  console.log(`Plan 2 - 2nd Route ID: ${route2Id}`);
  console.log(`Plan 5 - 2nd Route ID: ${route5Id}\n`);
  
  // Get all hotspot details for both routes
  const plan2Hotspots = await prisma.dvi_itinerary_route_hotspot_details.findMany({
    where: {
      itinerary_plan_ID: 2,
      itinerary_route_ID: route2Id,
      deleted: 0,
      status: 1
    },
    orderBy: { hotspot_order: 'asc' }
  });
  
  const plan5Hotspots = await prisma.dvi_itinerary_route_hotspot_details.findMany({
    where: {
      itinerary_plan_ID: 5,
      itinerary_route_ID: route5Id,
      deleted: 0,
      status: 1
    },
    orderBy: { hotspot_order: 'asc' }
  });
  
  console.log(`Plan 2 Route 2: ${plan2Hotspots.length} rows`);
  console.log(`Plan 5 Route 2: ${plan5Hotspots.length} rows\n`);
  
  // Compare row by row
  const maxRows = Math.max(plan2Hotspots.length, plan5Hotspots.length);
  
  for (let i = 0; i < maxRows; i++) {
    const p2 = plan2Hotspots[i];
    const p5 = plan5Hotspots[i];
    
    console.log(`\n━━━ ROW ${i + 1} ━━━`);
    
    if (!p2) {
      console.log('Plan 2: MISSING');
      console.log(`Plan 5: Order=${p5.hotspot_order}, Type=${p5.item_type}, Hotspot=${p5.hotspot_ID || 0}`);
      continue;
    }
    
    if (!p5) {
      console.log(`Plan 2: Order=${p2.hotspot_order}, Type=${p2.item_type}, Hotspot=${p2.hotspot_ID || 0}`);
      console.log('Plan 5: MISSING');
      continue;
    }
    
    // Compare key fields
    const diffs = [];
    
    if (p2.item_type !== p5.item_type) diffs.push(`item_type: ${p2.item_type} vs ${p5.item_type}`);
    if (p2.hotspot_order !== p5.hotspot_order) diffs.push(`order: ${p2.hotspot_order} vs ${p5.hotspot_order}`);
    
    const p2HotspotId = p2.hotspot_ID ? Number(p2.hotspot_ID) : 0;
    const p5HotspotId = p5.hotspot_ID ? Number(p5.hotspot_ID) : 0;
    if (p2HotspotId !== p5HotspotId) diffs.push(`hotspot_ID: ${p2HotspotId} vs ${p5HotspotId}`);
    
    const p2StartTime = p2.hotspot_start_time?.toISOString().substr(11, 8) || 'NULL';
    const p5StartTime = p5.hotspot_start_time?.toISOString().substr(11, 8) || 'NULL';
    if (p2StartTime !== p5StartTime) diffs.push(`start_time: ${p2StartTime} vs ${p5StartTime}`);
    
    const p2EndTime = p2.hotspot_end_time?.toISOString().substr(11, 8) || 'NULL';
    const p5EndTime = p5.hotspot_end_time?.toISOString().substr(11, 8) || 'NULL';
    if (p2EndTime !== p5EndTime) diffs.push(`end_time: ${p2EndTime} vs ${p5EndTime}`);
    
    const p2TravelTime = p2.hotspot_traveling_time?.toISOString().substr(11, 8) || 'NULL';
    const p5TravelTime = p5.hotspot_traveling_time?.toISOString().substr(11, 8) || 'NULL';
    if (p2TravelTime !== p5TravelTime) diffs.push(`travel_time: ${p2TravelTime} vs ${p5TravelTime}`);
    
    console.log(`Plan 2: Order=${p2.hotspot_order}, Type=${p2.item_type}, Hotspot=${p2HotspotId}`);
    console.log(`Plan 5: Order=${p5.hotspot_order}, Type=${p5.item_type}, Hotspot=${p5HotspotId}`);
    
    if (diffs.length > 0) {
      console.log(`❌ DIFFERENCES: ${diffs.join(', ')}`);
    } else {
      console.log('✅ MATCH');
    }
  }
  
  await prisma.$disconnect();
}

compareRoute2().catch(console.error);
