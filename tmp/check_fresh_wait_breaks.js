const { PrismaClient } = require('@prisma/client');
const prisma = new PrismaClient();

async function main() {
  const breaks = await prisma.dvi_itinerary_route_hotspot_details.findMany({
    where: {
      itinerary_plan_ID: 5,
      itinerary_route_ID: { in: [412, 413, 414] },
      item_type: 1,
      allow_break_hours: 1,
    },
    orderBy: [{ itinerary_route_ID: 'asc' }, { hotspot_order: 'asc' }],
  });

  console.log('\n=== Wait Breaks in Fresh Run (Routes 412-414) ===');
  if (breaks.length === 0) {
    console.log('NO WAIT BREAKS! The baseline does NOT create wait breaks.');
  } else {
    breaks.forEach(item => {
      const start = item.hotspot_start_time?.toISOString().substring(11, 19);
      const end = item.hotspot_end_time?.toISOString().substring(11, 19);
      console.log(`Route ${item.itinerary_route_ID}, Order ${item.hotspot_order}: ${start}-${end}`);
    });
  }

  await prisma.$disconnect();
}

main();
