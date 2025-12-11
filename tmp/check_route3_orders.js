const { PrismaClient } = require('@prisma/client');
const prisma = new PrismaClient();

async function checkRoute3Orders() {
  const rows = await prisma.dvi_itinerary_route_hotspot_details.findMany({
    where: {
      itinerary_plan_ID: 2,
      itinerary_route_ID: 429,
      deleted: 0
    },
    orderBy: { hotspot_order: 'asc' },
    select: { hotspot_order: true, item_type: true, hotspot_ID: true }
  });
  
  console.log('Plan 2 Route 3 (429) orders:');
  rows.forEach(r => console.log(`  Order ${r.hotspot_order}: Type ${r.item_type}, Hotspot ${r.hotspot_ID}`));
  
  const minOrder = Math.min(...rows.map(r => r.hotspot_order));
  console.log(`\nFirst order: ${minOrder}`);
  
  await prisma.$disconnect();
}

checkRoute3Orders();
