const { PrismaClient } = require('@prisma/client');
const prisma = new PrismaClient();

function formatTime(date) {
  if (!date) return 'NULL';
  if (typeof date === 'string') return date;
  const h = String(date.getUTCHours()).padStart(2, '0');
  const m = String(date.getUTCMinutes()).padStart(2, '0');
  const s = String(date.getUTCSeconds()).padStart(2, '0');
  return `${h}:${m}:${s}`;
}

async function comparePlan2Route3Timing() {
  console.log('\n=== PLAN 2 ROUTE 3 EXACT TIMING ===\n');
  
  const route3 = await prisma.dvi_itinerary_route_details.findFirst({
    where: { itinerary_plan_ID: 2, location_name: 'Pondicherry' }
  });
  
  const rows = await prisma.dvi_itinerary_route_hotspot_details.findMany({
    where: {
      itinerary_plan_ID: 2,
      itinerary_route_ID: route3.itinerary_route_ID,
      deleted: 0
    },
    orderBy: { hotspot_order: 'asc' }
  });
  
  const typeNames = {1: 'Refresh', 3: 'Travel', 4: 'Visit', 5: 'ToHotel', 6: 'AtHotel', 7: 'Return'};
  
  console.log(`Route ${route3.itinerary_route_ID}: ${route3.location_name} → ${route3.next_visiting_location}`);
  console.log(`Route starts: ${formatTime(route3.route_start_time)}`);
  console.log(`Route ends: ${formatTime(route3.route_end_time)}\n`);
  
  for (const row of rows) {
    const hotspot = row.hotspot_ID ? await prisma.dvi_hotspot_place.findUnique({
      where: { hotspot_ID: row.hotspot_ID }
    }) : null;
    
    console.log(`Order ${row.hotspot_order}: ${typeNames[row.item_type]}${row.hotspot_ID ? ` (Hotspot ${row.hotspot_ID})` : ''}`);
    if (hotspot) {
      console.log(`  Name: ${hotspot.hotspot_name}`);
    }
    console.log(`  Start: ${formatTime(row.hotspot_start_time)}`);
    console.log(`  End: ${formatTime(row.hotspot_end_time)}`);
    if (row.hotspot_traveling_time) {
      console.log(`  Travel Time: ${formatTime(row.hotspot_traveling_time)}`);
    }
    if (row.hotspot_travelling_distance) {
      console.log(`  Distance: ${row.hotspot_travelling_distance}km`);
    }
    console.log('');
  }
  
  await prisma.$disconnect();
}

comparePlan2Route3Timing();
