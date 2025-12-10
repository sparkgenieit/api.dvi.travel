const { PrismaClient } = require('@prisma/client');
const prisma = new PrismaClient();

async function main() {
  // Check PHP Plan 2 breaks
  const phpBreaks = await prisma.dvi_itinerary_route_hotspot_details.findMany({
    where: {
      itinerary_plan_ID: 2,
      item_type: 1,
      allow_break_hours: 1,
    },
    orderBy: [{ itinerary_route_ID: 'asc' }, { hotspot_order: 'asc' }],
  });

  console.log('\n=== PHP Plan 2 Wait Breaks (allow_break_hours=1) ===');
  phpBreaks.forEach(item => {
    const start = item.hotspot_start_time?.toISOString().substring(11, 19);
    const end = item.hotspot_end_time?.toISOString().substring(11, 19);
    console.log(`Route ${item.itinerary_route_ID}, Order ${item.hotspot_order}: ${start}-${end}`);
  });

  // Check NestJS Plan 5 breaks
  const nestBreaks = await prisma.dvi_itinerary_route_hotspot_details.findMany({
    where: {
      itinerary_plan_ID: 5,
      item_type: 1,
      allow_break_hours: 1,
    },
    orderBy: [{ itinerary_route_ID: 'asc' }, { hotspot_order: 'asc' }],
  });

  console.log('\n=== NestJS Plan 5 Wait Breaks (allow_break_hours=1) ===');
  nestBreaks.forEach(item => {
    const start = item.hotspot_start_time?.toISOString().substring(11, 19);
    const end = item.hotspot_end_time?.toISOString().substring(11, 19);
    console.log(`Route ${item.itinerary_route_ID}, Order ${item.hotspot_order}: ${start}-${end}`);
  });

  await prisma.$disconnect();
}

main();
