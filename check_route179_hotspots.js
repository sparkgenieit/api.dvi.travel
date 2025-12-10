const { PrismaClient } = require('@prisma/client');
const prisma = new PrismaClient();

async function checkRoute179() {
  const rows = await prisma.dvi_itinerary_route_hotspot_details.findMany({
    where: { itinerary_route_ID: 179, deleted: 0 },
    select: {
      hotspot_ID: true,
      item_type: true,
      hotspot_start_time: true,
      hotspot_end_time: true,
      hotspot_order: true
    },
    orderBy: { hotspot_order: 'asc' }
  });

  console.log('Route 179 hotspot details:');
  console.log('===========================\n');
  
  rows.forEach(r => {
    console.log(`Order ${r.hotspot_order}: Hotspot ${r.hotspot_ID}, item_type ${r.item_type}`);
    console.log(`  Time: ${r.hotspot_start_time} - ${r.hotspot_end_time}`);
  });

  await prisma.$disconnect();
}

checkRoute179().catch(console.error);
