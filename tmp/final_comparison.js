const { PrismaClient } = require('@prisma/client');
const prisma = new PrismaClient();

async function finalComparison() {
  console.log('\n=== FINAL COMPARISON: PLAN 2 vs PLAN 5 ===\n');
  
  const plan2Total = await prisma.dvi_itinerary_route_hotspot_details.count({
    where: { itinerary_plan_ID: 2, deleted: 0 }
  });
  
  const plan5Total = await prisma.dvi_itinerary_route_hotspot_details.count({
    where: { itinerary_plan_ID: 5, deleted: 0 }
  });
  
  console.log(`Plan 2: ${plan2Total} rows`);
  console.log(`Plan 5: ${plan5Total} rows\n`);
  
  if (plan2Total === plan5Total) {
    console.log('✅ Row count matches!\n');
  } else {
    console.log(`❌ Row count mismatch: ${plan2Total} vs ${plan5Total}\n`);
  }
  
  // Route-by-route summary
  const routes = [
    { name: 'Route 1', plan2: 427, plan5: 500 },
    { name: 'Route 2', plan2: 428, plan5: 501 },
    { name: 'Route 3', plan2: 429, plan5: 502 }
  ];
  
  for (const route of routes) {
    const plan2Rows = await prisma.dvi_itinerary_route_hotspot_details.findMany({
      where: { itinerary_plan_ID: 2, itinerary_route_ID: route.plan2, deleted: 0 },
      orderBy: { hotspot_order: 'asc' }
    });
    
    const plan5Rows = await prisma.dvi_itinerary_route_hotspot_details.findMany({
      where: { itinerary_plan_ID: 5, itinerary_route_ID: route.plan5, deleted: 0 },
      orderBy: { hotspot_order: 'asc' }
    });
    
    let matches = 0;
    let mismatches = 0;
    
    for (let i = 0; i < Math.min(plan2Rows.length, plan5Rows.length); i++) {
      const p2 = plan2Rows[i];
      const p5 = plan5Rows[i];
      
      if (p2.hotspot_order === p5.hotspot_order && 
          p2.item_type === p5.item_type &&
          p2.hotspot_ID === p5.hotspot_ID) {
        matches++;
      } else {
        mismatches++;
      }
    }
    
    const status = (plan2Rows.length === plan5Rows.length && mismatches === 0) ? '✅' : '⚠️';
    console.log(`${status} ${route.name}: ${plan2Rows.length} vs ${plan5Rows.length} rows, ${matches} matches, ${mismatches} mismatches`);
  }
  
  console.log('\n');
  await prisma.$disconnect();
}

finalComparison();
