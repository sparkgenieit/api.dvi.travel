const { PrismaClient } = require('@prisma/client');
const prisma = new PrismaClient();

async function checkHotspot18InPlan5() {
  const routes = await prisma.dvi_itinerary_route_hotspot_details.findMany({
    where: {
      itinerary_plan_ID: 5,
      hotspot_ID: 18
    },
    orderBy: { itinerary_route_ID: 'asc' }
  });

  console.log('\n=== HOTSPOT 18 IN PLAN 5 ===');
  if (routes.length === 0) {
    console.log('❌ NOT FOUND IN ANY ROUTE!');
  } else {
    routes.forEach(r => {
      console.log(`Route ${r.itinerary_route_ID}: Order ${r.hotspot_order}, Type ${r.item_type}`);
    });
  }

  await prisma.$disconnect();
}

checkHotspot18InPlan5().catch(console.error);
