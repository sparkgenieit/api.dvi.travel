const { PrismaClient } = require('@prisma/client');
const prisma = new PrismaClient();

async function main() {
  // Get Plan 5 Route 2 hotspots (now route 425)
  const rows = await prisma.dvi_itinerary_route_hotspot_details.findMany({
    where: {
      itinerary_plan_ID: 5,
      itinerary_route_ID: 425,
      item_type: 4, // Visits only
      deleted: 0,
      status: 1,
    },
    orderBy: { hotspot_order: 'asc' },
    select: {
      hotspot_ID: true,
    }
  });

  const hotspotIds = rows.map(r => r.hotspot_ID);
  
  console.log('\n=== ROUTE 2 (425) HOTSPOTS ===');
  console.log('Expected: [4, 18, 21, 19, 17, 678]');
  console.log('Actual:  ', JSON.stringify(hotspotIds));
  
  const match = JSON.stringify(hotspotIds) === JSON.stringify([4, 18, 21, 19, 17, 678]);
  console.log(match ? '\n✅ PERFECT MATCH!' : '\n❌ Still different');
}

main()
  .catch(console.error)
  .finally(() => prisma.$disconnect());
