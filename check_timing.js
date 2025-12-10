const { PrismaClient } = require('@prisma/client');
const prisma = new PrismaClient();

async function checkHotspotTimings() {
  console.log('\n=== CHECKING HOTSPOT TIMINGS FOR TUESDAY (PHP dow=1) ===\n');
  
  // Check Kapaleeshwarar (4), Marina Beach (5), Light House (294)
  const hotspotIds = [4, 5, 294];
  
  for (const id of hotspotIds) {
    console.log(`\n--- Hotspot ID ${id} ---`);
    
    const hotspot = await prisma.dvi_hotspot_place.findFirst({
      where: { hotspot_ID: id }
    });
    
    console.log(`Name: ${hotspot.hotspot_name}`);
    console.log(`Priority: ${hotspot.hotspot_priority}`);
    console.log(`Stay time: ${hotspot.hotspot_stay_time} minutes`);
    console.log(`Location: ${hotspot.hotspot_location}`);
    
    const timings = await prisma.dvi_hotspot_timing.findMany({
      where: {
        hotspot_ID: id,
        deleted: 0,
        status: 1
      },
      orderBy: { hotspot_timing_day: 'asc' }
    });
    
    console.log(`\nTimings (${timings.length} rows):`);
    if (timings.length === 0) {
      console.log('  NO TIMING RESTRICTIONS - Open all days!');
    } else {
      console.table(timings.map(t => ({
        day: t.hotspot_timing_day,
        day_name: ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'][t.hotspot_timing_day],
        start: t.hotspot_start_time,
        end: t.hotspot_end_time,
        status: t.status
      })));
    }
  }
  
  // Check PHP's day calculation for Plan 2 Route 178
  console.log('\n\n=== PHP DAY CALCULATION ===');
  const route178 = await prisma.dvi_itinerary_route_details.findFirst({
    where: { itinerary_route_ID: 178 }
  });
  
  if (route178) {
    const routeDate = new Date(route178.itinerary_route_date);
    console.log(`Route 178 date: ${routeDate.toISOString().split('T')[0]}`);
    console.log(`JS getDay(): ${routeDate.getDay()} (0=Sunday)`);
    const phpDow = (routeDate.getDay() + 6) % 7;
    console.log(`PHP dow (Monday=0): ${phpDow} = ${['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'][phpDow]}`);
  }
  
  await prisma.$disconnect();
}

checkHotspotTimings().catch(e => {
  console.error(e);
  process.exit(1);
});
