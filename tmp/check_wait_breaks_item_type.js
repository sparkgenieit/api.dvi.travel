const { PrismaClient } = require('@prisma/client');
const prisma = new PrismaClient();

async function main() {
  // Check PHP Plan 2 Route 179 for wait breaks
  const phpBreaks = await prisma.dvi_itinerary_route_hotspot_details.findMany({
    where: {
      itinerary_plan_ID: 2,
      itinerary_route_ID: 179,
      allow_break_hours: 1,
    },
    orderBy: { hotspot_order: 'asc' },
  });

  console.log('\n=== PHP Plan 2 Route 179 - Wait Breaks (allow_break_hours=1) ===');
  phpBreaks.forEach(item => {
    const start = item.hotspot_start_time?.toISOString().substring(11, 19);
    const end = item.hotspot_end_time?.toISOString().substring(11, 19);
    console.log(`Order ${item.hotspot_order}: item_type=${item.item_type}, hotspot_ID=${item.hotspot_ID}, ${start}-${end}`);
  });

  // Check NestJS Plan 5 Route 412 for wait breaks
  const nestjsBreaks = await prisma.dvi_itinerary_route_hotspot_details.findMany({
    where: {
      itinerary_plan_ID: 5,
      itinerary_route_ID: 412,
      allow_break_hours: 1,
    },
    orderBy: { hotspot_order: 'asc' },
  });

  console.log('\n=== NestJS Plan 5 Route 412 - Wait Breaks (allow_break_hours=1) ===');
  nestjsBreaks.forEach(item => {
    const start = item.hotspot_start_time?.toISOString().substring(11, 19);
    const end = item.hotspot_end_time?.toISOString().substring(11, 19);
    console.log(`Order ${item.hotspot_order}: item_type=${item.item_type}, hotspot_ID=${item.hotspot_ID}, ${start}-${end}`);
  });

  await prisma.$disconnect();
}

main();
