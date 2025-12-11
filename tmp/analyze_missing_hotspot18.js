const { PrismaClient } = require('@prisma/client');
const prisma = new PrismaClient();

async function main() {
  console.log('\n=== Analyzing Hotspot 18 Routing ===');
  
  // Route 419 (Route 2) ends at what time?
  const route419 = await prisma.dvi_itinerary_route_details.findUnique({
    where: { itinerary_route_ID: 419 },
  });
  
  console.log('\nRoute 419 (Route 2):');
  console.log('  Date:', route419.itinerary_route_date?.toISOString().substring(0, 10));
  console.log('  Start:', route419.route_start_time?.toISOString().substring(11, 19));
  console.log('  End:', route419.route_end_time?.toISOString().substring(11, 19));
  
  // What's the last hotspot in Route 419?
  const lastItems = await prisma.dvi_itinerary_route_hotspot_details.findMany({
    where: {
      itinerary_plan_ID: 5,
      itinerary_route_ID: 419,
    },
    orderBy: { hotspot_order: 'desc' },
    take: 3,
  });
  
  console.log('\nLast items in Route 419:');
  lastItems.reverse().forEach(item => {
    const start = item.hotspot_start_time?.toISOString().substring(11, 19);
    const end = item.hotspot_end_time?.toISOString().substring(11, 19);
    console.log(`  Order ${item.hotspot_order}: type=${item.item_type}, h=${item.hotspot_ID}, ${start}-${end}`);
  });
  
  // Check hotspot 18 location and priority
  const h18 = await prisma.dvi_hotspot_place.findUnique({
    where: { hotspot_ID: 18 },
    select: {
      hotspot_ID: true,
      hotspot_name: true,
      hotspot_location: true,
      hotspot_priority: true,
      hotspot_duration: true,
    },
  });
  
  console.log('\nHotspot 18:');
  console.log('  Name:', h18.hotspot_name);
  console.log('  Location:', h18.hotspot_location);
  console.log('  Priority:', h18.hotspot_priority);
  console.log('  Duration:', h18.hotspot_duration?.toISOString().substring(11, 19));
  
  console.log('\n=== PHP Comparison ===');
  console.log('PHP Route 2 (179): [4, 18, 21, 19, 17, 678]');
  console.log('NestJS Route 2 (419): [4, 21, 19, 17, 677, 678, 679]');
  console.log('\nHotspot 18 should be BETWEEN 4 and 21, but it\'s missing!');

  await prisma.$disconnect();
}

main();
