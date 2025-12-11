const { PrismaClient } = require('@prisma/client');
const prisma = new PrismaClient();

async function checkPlan2Routes() {
  console.log('\n=== PLAN 2 (PHP) ROUTE ANALYSIS ===\n');
  
  const routes = await prisma.dvi_itinerary_route_details.findMany({
    where: {
      itinerary_plan_ID: 2,
      deleted: 0
    },
    orderBy: [
      { itinerary_route_date: 'asc' },
      { itinerary_route_ID: 'asc' }
    ]
  });
  
  console.log(`Total routes: ${routes.length}\n`);
  
  for (const [index, route] of routes.entries()) {
    console.log(`Route ${index + 1} (ID ${route.itinerary_route_ID}): ${route.location_name} → ${route.next_visiting_location}`);
    
    const allRows = await prisma.dvi_itinerary_route_hotspot_details.findMany({
      where: {
        itinerary_plan_ID: 2,
        itinerary_route_ID: route.itinerary_route_ID,
        deleted: 0
      },
      orderBy: { hotspot_order: 'asc' }
    });
    
    const typeNames = {1: 'Refresh', 3: 'Travel', 4: 'Visit', 5: 'ToHotel', 6: 'AtHotel', 7: 'Return'};
    
    console.log(`  Total rows: ${allRows.length}`);
    console.log(`  Row types:`);
    
    const typeCount = {};
    allRows.forEach(r => {
      const typeName = typeNames[r.item_type];
      typeCount[typeName] = (typeCount[typeName] || 0) + 1;
    });
    
    for (const [type, count] of Object.entries(typeCount)) {
      console.log(`    ${type}: ${count}`);
    }
    
    console.log(`  First order: ${allRows[0]?.hotspot_order} (${typeNames[allRows[0]?.item_type]})`);
    console.log(`  Last order: ${allRows[allRows.length - 1]?.hotspot_order} (${typeNames[allRows[allRows.length - 1]?.item_type]})`);
    console.log('');
  }
  
  await prisma.$disconnect();
}

checkPlan2Routes();
