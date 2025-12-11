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

async function analyzePlan2Route2Timeline() {
  console.log('\n=== PLAN 2 ROUTE 2 TIMELINE (PHP) ===\n');
  
  const rows = await prisma.dvi_itinerary_route_hotspot_details.findMany({
    where: {
      itinerary_plan_ID: 2,
      itinerary_route_ID: 428,
      deleted: 0
    },
    orderBy: { hotspot_order: 'asc' }
  });
  
  const typeNames = {1: 'Refresh', 3: 'Travel', 4: 'Visit', 5: 'ToHotel', 6: 'AtHotel', 7: 'Return'};
  
  for (const row of rows) {
    const type = typeNames[row.item_type];
    const hotspot = row.hotspot_ID ? await prisma.dvi_hotspot_place.findUnique({
      where: { hotspot_ID: row.hotspot_ID }
    }) : null;
    
    console.log(`Order ${row.hotspot_order}: ${type}${row.hotspot_ID ? ` - Hotspot ${row.hotspot_ID}` : ''}`);
    if (hotspot) {
      console.log(`  Name: ${hotspot.hotspot_name}`);
    }
    console.log(`  Start: ${formatTime(row.hotspot_start_time)}`);
    console.log(`  End: ${formatTime(row.hotspot_end_time)}`);
    console.log(`  Travel Time: ${formatTime(row.hotspot_traveling_time)}`);
    console.log(`  Distance: ${row.hotspot_travelling_distance}km`);
    console.log('');
  }
  
  // Check opening hours for hotspots 18, 25, 20 that NestJS skipped
  console.log('\n=== OPENING HOURS FOR SKIPPED HOTSPOTS ===\n');
  
  const skipped = [18, 25, 20];
  for (const id of skipped) {
    const hotspot = await prisma.dvi_hotspot_place.findUnique({
      where: { hotspot_ID: id }
    });
    
    const hours = await prisma.dvi_hotspot_visiting_hour.findMany({
      where: {
        hotspot_ID: id,
        deleted: 0,
        status: 1
      }
    });
    
    console.log(`Hotspot ${id}: ${hotspot?.hotspot_name}`);
    console.log(`  Priority: ${hotspot?.hotspot_priority || 0}`);
    console.log(`  Opening hours:`);
    hours.forEach(h => {
      const open = formatTime(h.opening_time);
      const close = formatTime(h.closing_time);
      console.log(`    ${open} - ${close} (days: ${h.day_of_week})`);
    });
    console.log('');
  }
  
  await prisma.$disconnect();
}

analyzePlan2Route2Timeline();
