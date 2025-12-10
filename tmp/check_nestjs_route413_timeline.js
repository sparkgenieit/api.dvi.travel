const { PrismaClient } = require('@prisma/client');
const prisma = new PrismaClient();

async function main() {
  // Get NestJS Plan 5 Route 413 timeline around hotspot 18
  const nestjsItems = await prisma.dvi_itinerary_route_hotspot_details.findMany({
    where: {
      itinerary_plan_ID: 5,
      itinerary_route_ID: 413,
    },
    orderBy: { hotspot_order: 'asc' },
  });

  console.log('\n=== NestJS Plan 5 Route 413 Timeline ===');
  nestjsItems.forEach(item => {
    const start = item.hotspot_start_time?.toISOString().substring(11, 19);
    const end = item.hotspot_end_time?.toISOString().substring(11, 19);
    const typeNames = {1: 'Break', 3: 'Travel', 4: 'Hotspot', 5: 'ToHotel', 6: 'Hotel', 7: 'Return'};
    console.log(`${item.hotspot_order}. ${typeNames[item.item_type] || item.item_type} hotspot=${item.hotspot_ID} ${start}-${end} (break=${item.allow_break_hours})`);
  });

  await prisma.$disconnect();
}

main();
