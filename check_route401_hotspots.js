const { PrismaClient } = require('@prisma/client');
const prisma = new PrismaClient();

async function checkRoute401() {
  const timeline = await prisma.dvi_itinerary_route_hotspot_details.findMany({
    where: { itinerary_route_ID: 401 },
    orderBy: { hotspot_order: 'asc' }
  });

  const hotspotIds = timeline
    .filter(h => h.hotspot_ID !== null && h.hotspot_ID > 0)
    .map(h => h.hotspot_ID);

  console.log('\n=== ROUTE 2 (Route 401) HOTSPOT IDs ===');
  console.log('NestJS Plan 5:', hotspotIds);
  console.log('PHP Plan 2:    [ 4, 18, 21, 19, 17, 678 ]');
  console.log(JSON.stringify(hotspotIds) === JSON.stringify([4, 18, 21, 19, 17, 678]) ? '\n✅ PERFECT MATCH!' : '\n❌ MISMATCH');

  await prisma.$disconnect();
}

checkRoute401().catch(console.error);
