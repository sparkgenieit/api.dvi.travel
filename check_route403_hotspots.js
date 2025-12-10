const { PrismaClient } = require('@prisma/client');
const prisma = new PrismaClient();

async function checkRoute403Hotspots() {
  const route = await prisma.dvi_itinerary_route_hotspot_details.findMany({
    where: { itinerary_route_ID: 403, hotspot_ID: { gt: 0 } },
    orderBy: { hotspot_order: 'asc' }
  });

  console.log('\n=== ROUTE 403 (Plan 5 Route 1) HOTSPOTS ===');
  const hotspotIds = [...new Set(route.map(r => r.hotspot_ID))];
  console.log(hotspotIds);
  console.log('\nExpected: [5]');
  console.log(hotspotIds.length === 1 && hotspotIds[0] === 5 ? '✅ MATCH' : '❌ MISMATCH');

  await prisma.$disconnect();
}

checkRoute403Hotspots().catch(console.error);
