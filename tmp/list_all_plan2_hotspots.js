const { PrismaClient } = require('@prisma/client');
const prisma = new PrismaClient();

async function listAllPlan2Hotspots() {
  console.log('\n=== ALL HOTSPOTS IN PLAN 2 (PHP) ===\n');
  
  const routes = await prisma.dvi_itinerary_route_details.findMany({
    where: { itinerary_plan_ID: 2, deleted: 0 },
    orderBy: [{ itinerary_route_date: 'asc' }, { itinerary_route_ID: 'asc' }]
  });
  
  for (const route of routes) {
    console.log(`Route ${route.itinerary_route_ID}: ${route.location_name} → ${route.next_visiting_location}`);
    
    const visits = await prisma.dvi_itinerary_route_hotspot_details.findMany({
      where: {
        itinerary_plan_ID: 2,
        itinerary_route_ID: route.itinerary_route_ID,
        item_type: 4,
        deleted: 0
      },
      orderBy: { hotspot_order: 'asc' }
    });
    
    for (const v of visits) {
      const h = await prisma.dvi_hotspot_place.findUnique({
        where: { hotspot_ID: v.hotspot_ID }
      });
      console.log(`  ${v.hotspot_ID}: ${h?.hotspot_name} (Priority: ${h?.hotspot_priority || 0})`);
    }
    console.log('');
  }
  
  await prisma.$disconnect();
}

listAllPlan2Hotspots();
