const { PrismaClient } = require('@prisma/client');
const prisma = new PrismaClient();

async function checkRoute397() {
  // Get route details
  const route = await prisma.dvi_itinerary_route_details.findFirst({
    where: { itinerary_route_ID: 397 }
  });

  console.log('\n=== ROUTE 397 DETAILS ===');
  console.log('Start:', route.start_from);
  console.log('End:', route.end_destination);
  console.log('Route Start Time:', route.route_start_time);

  // Get timeline
  const timeline = await prisma.dvi_itinerary_route_hotspot_details.findMany({
    where: { itinerary_route_ID: 397 },
    orderBy: { hotspot_start_time: 'asc' }
  });

  console.log('\n=== TIMELINE ===');
  timeline.forEach(h => {
    const start = h.hotspot_start_time;
    const startStr = `${String(start.getUTCHours()).padStart(2, '0')}:${String(start.getUTCMinutes()).padStart(2, '0')}`;
    const end = h.hotspot_end_time;
    const endStr = `${String(end.getUTCHours()).padStart(2, '0')}:${String(end.getUTCMinutes()).padStart(2, '0')}`;
    console.log(`${startStr} - ${endStr}: ${h.via_location_name} (hotspot: ${h.hotspot_ID})`);
  });

  const hotspotIds = timeline
    .filter(h => h.hotspot_ID !== null)
    .map(h => h.hotspot_ID);

  console.log('\n=== HOTSPOT IDs ===');
  console.log(hotspotIds);
  console.log('\n✅ Expected: [5]');
  console.log('✅ PHP Route 1 has: [5]');
  console.log(JSON.stringify(hotspotIds) === JSON.stringify([5]) ? '✅ MATCH!' : '❌ MISMATCH!');

  await prisma.$disconnect();
}

checkRoute397().catch(console.error);
