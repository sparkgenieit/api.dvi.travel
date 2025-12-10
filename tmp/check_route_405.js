const { PrismaClient } = require('@prisma/client');
const prisma = new PrismaClient();

async function main() {
  const route405 = await prisma.dvi_itinerary_route_hotspot_details.findMany({
    where: {
      itinerary_plan_ID: 5,
      itinerary_route_ID: 405,
    },
    orderBy: {
      hotspot_order: 'asc',
    },
  });

  console.log('\n=== Route 405 (Plan 5 Route 1) ===');
  console.log('Total items:', route405.length);
  route405.forEach((item) => {
    console.log(`Order ${item.hotspot_order}: item_type=${item.item_type}, hotspot_ID=${item.hotspot_ID}, ${item.hotspot_start_time} - ${item.hotspot_end_time}, allow_break_hours=${item.allow_break_hours}`);
  });

  await prisma.$disconnect();
}

main();
