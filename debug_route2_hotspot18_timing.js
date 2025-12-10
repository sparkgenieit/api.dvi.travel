const { PrismaClient } = require('@prisma/client');
const prisma = new PrismaClient();

async function debugRoute2Hotspot18() {
  // Get Route 2 details
  const route = await prisma.dvi_itinerary_route_details.findFirst({
    where: { itinerary_route_ID: 401 }
  });

  console.log('\n=== ROUTE 401 (NestJS Plan 5 Route 2) ===');
  console.log('Date:', route.itinerary_route_date.toISOString().split('T')[0]);
  console.log('Start:', `${String(route.route_start_time.getUTCHours()).padStart(2, '0')}:${String(route.route_start_time.getUTCMinutes()).padStart(2, '0')}`);
  console.log('End:', `${String(route.route_end_time.getUTCHours()).padStart(2, '0')}:${String(route.route_end_time.getUTCMinutes()).padStart(2, '0')}`);

  // Get day of week (PHP format: Monday=0)
  const date = new Date(route.itinerary_route_date);
  const phpDow = (date.getDay() + 6) % 7;
  const days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];
  console.log('Day:', days[phpDow], `(PHP dow=${phpDow})`);

  // Get hotspot 18 timing
  const timing = await prisma.$queryRawUnsafe(`
    SELECT 
      hotspot_timing_ID,
      hotspot_timing_day,
      CAST(hotspot_start_time AS CHAR) as opening,
      CAST(hotspot_end_time AS CHAR) as closing
    FROM dvi_hotspot_timing
    WHERE hotspot_ID = 18
    ORDER BY hotspot_timing_ID
  `);

  console.log('\n=== HOTSPOT 18 (Auroville) TIMING ===');
  if (timing.length === 0) {
    console.log('No timing restrictions (open 24/7)');
  } else {
    timing.forEach(t => {
      console.log(`Day ${t.hotspot_timing_day}: ${t.opening} - ${t.closing}`);
    });
    
    const todayTiming = timing.filter(t => t.hotspot_timing_day === phpDow);
    if (todayTiming.length > 0) {
      console.log(`\n✅ Open on ${days[phpDow]}:`);
      todayTiming.forEach(t => console.log(`  ${t.opening} - ${t.closing}`));
    } else {
      console.log(`\n❌ CLOSED on ${days[phpDow]}`);
    }
  }

  // Get Route 2 timeline to see when hotspot would be visited
  const timeline = await prisma.dvi_itinerary_route_hotspot_details.findMany({
    where: { itinerary_route_ID: 401 },
    orderBy: { hotspot_order: 'asc' }
  });

  console.log('\n=== ROUTE 401 TIMELINE ===');
  timeline.forEach((item, i) => {
    if (item.hotspot_ID > 0) {
      const start = `${String(item.hotspot_start_time.getUTCHours()).padStart(2, '0')}:${String(item.hotspot_start_time.getUTCMinutes()).padStart(2, '0')}`;
      const end = `${String(item.hotspot_end_time.getUTCHours()).padStart(2, '0')}:${String(item.hotspot_end_time.getUTCMinutes()).padStart(2, '0')}`;
      console.log(`${i+1}. Hotspot ${item.hotspot_ID}: ${start}-${end}`);
      if (i === 1) {
        console.log('   ^^^ First hotspot visit time');
      }
    }
  });

  // Calculate when hotspot 18 WOULD be visited if it were selected first
  const firstHotspot = timeline.find(item => item.hotspot_ID > 0);
  if (firstHotspot) {
    const visitTime = `${String(firstHotspot.hotspot_start_time.getUTCHours()).padStart(2, '0')}:${String(firstHotspot.hotspot_start_time.getUTCMinutes()).padStart(2, '0')}`;
    console.log(`\n=== ANALYSIS ===`);
    console.log(`First hotspot (${firstHotspot.hotspot_ID}) visited at: ${visitTime}`);
    console.log(`If hotspot 18 were selected first, it would be visited around: ${visitTime}`);
    console.log(`Hotspot 18 duration: 02:00:00 (2 hours)`);
  }

  await prisma.$disconnect();
}

debugRoute2Hotspot18().catch(console.error);
