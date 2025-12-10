const { PrismaClient } = require('@prisma/client');
const prisma = new PrismaClient();

async function checkRoute232() {
  const hotspots = await prisma.dvi_itinerary_route_hotspot_details.findMany({
    where: {
      itinerary_plan_ID: 5,
      itinerary_route_ID: 232,
      deleted: 0,
      status: 1,
      item_type: { in: [3, 4] }
    },
    orderBy: { hotspot_order: 'asc' }
  });
  
  const ids = [...new Set(hotspots.map(h => h.hotspot_ID))];
  console.log('Route 232 hotspot IDs:', ids.join(', '));
  console.log('Total rows:', hotspots.length);
  
  await prisma.$disconnect();
}

checkRoute232();
