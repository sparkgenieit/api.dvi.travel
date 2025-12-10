const { PrismaClient } = require('@prisma/client');
const prisma = new PrismaClient();

async function debugRoute397() {
  // Get full route details
  const route = await prisma.dvi_itinerary_route_details.findFirst({
    where: { itinerary_route_ID: 400 }
  });

  console.log('\n=== FULL ROUTE 400 DETAILS ===');
  console.log(JSON.stringify(route, (key, value) => 
    typeof value === 'bigint' ? value.toString() : value
  , 2));

  // Get all timeline items with all fields
  const timeline = await prisma.dvi_itinerary_route_hotspot_details.findMany({
    where: { itinerary_route_ID: 400 },
    orderBy: { hotspot_order: 'asc' }
  });

  console.log('\n=== FULL TIMELINE (by order) ===');
  timeline.forEach((h, i) => {
    console.log(`\n--- Item ${i+1} (order: ${h.hotspot_order}) ---`);
    console.log('Type:', h.item_type);
    console.log('Hotspot ID:', h.hotspot_ID);
    console.log('Location:', h.via_location_name);
    const start = h.hotspot_start_time;
    const end = h.hotspot_end_time;
    console.log('Start:', `${String(start.getUTCHours()).padStart(2, '0')}:${String(start.getUTCMinutes()).padStart(2, '0')}`);
    console.log('End:', `${String(end.getUTCHours()).padStart(2, '0')}:${String(end.getUTCMinutes()).padStart(2, '0')}`);
  });

  await prisma.$disconnect();
}

debugRoute397().catch(console.error);
