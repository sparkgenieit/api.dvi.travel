const { PrismaClient } = require('@prisma/client');
const prisma = new PrismaClient();

async function main() {
  const breaks = await prisma.dvi_itinerary_route_hotspot_details.findMany({
    where: {
      itinerary_plan_ID: 5,
      itinerary_route_ID: 410,
      item_type: 1,
    },
    orderBy: { hotspot_order: 'asc' },
  });

  console.log('\n=== Route 410 Breaks (item_type=1) ===');
  breaks.forEach(item => {
    const start = item.hotspot_start_time?.toISOString().substring(11, 19);
    const end = item.hotspot_end_time?.toISOString().substring(11, 19);
    console.log(`Order ${item.hotspot_order}: ${start}-${end}, allow_break_hours=${item.allow_break_hours}, hotspot_ID=${item.hotspot_ID}`);
  });

  await prisma.$disconnect();
}

main();
