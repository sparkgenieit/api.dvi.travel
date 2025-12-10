const { PrismaClient } = require('@prisma/client');
const prisma = new PrismaClient();

async function main() {
  const h25 = await prisma.dvi_hotspot_place.findUnique({
    where: { hotspot_ID: 25 },
    select: {
      hotspot_ID: true,
      hotspot_name: true,
      hotspot_location: true,
      hotspot_priority: true,
      hotspot_duration: true,
    },
  });

  console.log('\n=== Hotspot 25 Details ===');
  console.log(JSON.stringify(h25, null, 2));

  // Check timing for day 3 (Thursday - Route 2)
  const timing = await prisma.dvi_hotspot_timing.findMany({
    where: {
      hotspot_ID: 25,
      hotspot_timing_day: 3,
      deleted: 0,
      status: 1,
    },
    orderBy: { hotspot_start_time: 'asc' },
  });

  console.log('\n=== Hotspot 25 Timing (Thursday) ===');
  timing.forEach(t => {
    const start = t.hotspot_start_time?.toISOString().substring(11, 19) || 'null';
    const end = t.hotspot_end_time?.toISOString().substring(11, 19) || 'null';
    console.log(`${start} - ${end}`);
  });

  // Check Route 2 details
  const route2Items = await prisma.dvi_itinerary_route_hotspot_details.findMany({
    where: {
      itinerary_plan_ID: 5,
      itinerary_route_ID: 410,
    },
    orderBy: { hotspot_order: 'asc' },
  });

  console.log('\n=== Route 410 Timeline ===');
  route2Items.forEach(item => {
    const start = item.hotspot_start_time?.toISOString().substring(11, 19);
    const end = item.hotspot_end_time?.toISOString().substring(11, 19);
    console.log(`Order ${item.hotspot_order}: type=${item.item_type}, hotspot=${item.hotspot_ID}, ${start}-${end}`);
  });

  await prisma.$disconnect();
}

main();
