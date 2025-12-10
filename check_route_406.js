const { PrismaClient } = require('@prisma/client');
const prisma = new PrismaClient();

async function checkRoute406() {
  const rows = await prisma.dvi_itinerary_route_hotspot_details.findMany({
    where: { itinerary_plan_ID: 5, itinerary_route_ID: 406 },
    orderBy: { hotspot_order: 'asc' }
  });

  console.log(`\n=== ROUTE 406 (Plan 5) - ${rows.length} items ===`);
  rows.forEach(r => {
    const start = `${String(r.hotspot_start_time.getUTCHours()).padStart(2, '0')}:${String(r.hotspot_start_time.getUTCMinutes()).padStart(2, '0')}`;
    const end = `${String(r.hotspot_end_time.getUTCHours()).padStart(2, '0')}:${String(r.hotspot_end_time.getUTCMinutes()).padStart(2, '0')}`;
    console.log(`${r.hotspot_order}. item_type=${r.item_type} hotspot=${r.hotspot_ID} ${start}-${end} allow_break=${r.allow_break_hours || 0}`);
  });

  await prisma.$disconnect();
}

checkRoute406();
