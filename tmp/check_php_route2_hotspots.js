const { PrismaClient } = require('@prisma/client');
const prisma = new PrismaClient();

async function main() {
  // PHP Plan 2, Route 2 (route 179, second date)
  const phpRoute2Items = await prisma.dvi_itinerary_route_hotspot_details.findMany({
    where: {
      itinerary_plan_ID: 2,
      itinerary_route_ID: 179,
      item_type: 4, // Only hotspot visits
    },
    orderBy: { hotspot_order: 'asc' },
  });

  console.log('\n=== PHP Plan 2, Route 179 Hotspots ===');
  phpRoute2Items.forEach(item => {
    const start = item.hotspot_start_time?.toISOString().substring(11, 19);
    const end = item.hotspot_end_time?.toISOString().substring(11, 19);
    console.log(`Hotspot ${item.hotspot_ID}: ${start}-${end}`);
  });

  const hotspotIds = phpRoute2Items.map(item => item.hotspot_ID);
  console.log(`\nHotspot IDs: [${hotspotIds.join(', ')}]`);

  await prisma.$disconnect();
}

main();
