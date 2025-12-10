const { PrismaClient } = require('@prisma/client');
const prisma = new PrismaClient();

async function main() {
  // Check ALL plans for allow_break_hours=1
  const allBreaks = await prisma.dvi_itinerary_route_hotspot_details.findMany({
    where: {
      allow_break_hours: 1,
    },
    orderBy: [
      { itinerary_plan_ID: 'asc' },
      { itinerary_route_ID: 'asc' },
      { hotspot_order: 'asc' },
    ],
    take: 20,
  });

  console.log('\n=== ALL Wait Breaks (allow_break_hours=1) ===');
  allBreaks.forEach(item => {
    const start = item.hotspot_start_time?.toISOString().substring(11, 19);
    const end = item.hotspot_end_time?.toISOString().substring(11, 19);
    console.log(`Plan ${item.itinerary_plan_ID}, Route ${item.itinerary_route_ID}, Order ${item.hotspot_order}: item_type=${item.item_type}, hotspot=${item.hotspot_ID}, ${start}-${end}`);
  });

  await prisma.$disconnect();
}

main();
