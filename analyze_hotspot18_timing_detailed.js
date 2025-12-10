const { PrismaClient } = require('@prisma/client');
const prisma = new PrismaClient();

async function analyzeHotspot18Timing() {
  console.log('\n=== ANALYZING WHY HOTSPOT 18 IS SKIPPED IN ROUTE 2 ===\n');

  // Get Route 2 details
  const route = await prisma.dvi_itinerary_route_details.findFirst({
    where: { itinerary_route_ID: 404 }
  });

  const routeStart = `${String(route.route_start_time.getUTCHours()).padStart(2, '0')}:${String(route.route_start_time.getUTCMinutes()).padStart(2, '0')}`;
  const routeEnd = `${String(route.route_end_time.getUTCHours()).padStart(2, '0')}:${String(route.route_end_time.getUTCMinutes()).padStart(2, '0')}`;

  console.log('ROUTE 404 INFO:');
  console.log(`  Start: ${routeStart}, End: ${routeEnd}`);
  console.log(`  Date: ${route.itinerary_route_date.toISOString().split('T')[0]}`);

  // Get hotspot 18 details
  const hotspot18 = await prisma.dvi_hotspot_place.findUnique({
    where: { hotspot_ID: 18 }
  });

  const duration18 = hotspot18.hotspot_duration;
  const durationStr = `${String(duration18.getUTCHours()).padStart(2, '0')}:${String(duration18.getUTCMinutes()).padStart(2, '0')}`;
  
  console.log('\nHOTSPOT 18 (Auroville):');
  console.log(`  Duration: ${durationStr} (${duration18.getUTCHours()} hours)`);
  console.log(`  Location: ${hotspot18.hotspot_location}`);
  console.log(`  Priority: ${hotspot18.hotspot_priority}`);

  // Get operating hours
  const timing = await prisma.dvi_hotspot_timing.findMany({
    where: { hotspot_ID: 18 }
  });

  console.log(`\n  Operating Hours (${timing.length} time slots):`);
  timing.slice(0, 14).forEach(t => {
    const open = t.hotspot_start_time ? `${String(t.hotspot_start_time.getUTCHours()).padStart(2, '0')}:${String(t.hotspot_start_time.getUTCMinutes()).padStart(2, '0')}` : 'null';
    const close = t.hotspot_end_time ? `${String(t.hotspot_end_time.getUTCHours()).padStart(2, '0')}:${String(t.hotspot_end_time.getUTCMinutes()).padStart(2, '0')}` : 'null';
    console.log(`    Day ${t.hotspot_timing_day}: ${open} - ${close}`);
  });

  // Get actual Route 2 timeline
  const timeline = await prisma.dvi_itinerary_route_hotspot_details.findMany({
    where: { itinerary_route_ID: 404 },
    orderBy: { hotspot_order: 'asc' }
  });

  console.log('\nACTUAL ROUTE 404 TIMELINE:');
  timeline.filter(t => t.hotspot_ID > 0).forEach(t => {
    const start = `${String(t.hotspot_start_time.getUTCHours()).padStart(2, '0')}:${String(t.hotspot_start_time.getUTCMinutes()).padStart(2, '0')}`;
    const end = `${String(t.hotspot_end_time.getUTCHours()).padStart(2, '0')}:${String(t.hotspot_end_time.getUTCMinutes()).padStart(2, '0')}`;
    const duration = t.hotspot_end_time.getTime() - t.hotspot_start_time.getTime();
    const durationMins = Math.round(duration / 60000);
    console.log(`  ${t.item_type === 3 ? 'Travel' : 'Visit'} Hotspot ${t.hotspot_ID}: ${start} - ${end} (${durationMins} mins)`);
  });

  // Calculate where hotspot 18 WOULD fit
  const firstVisit = timeline.find(t => t.hotspot_ID === 4 && t.item_type === 4);
  if (firstVisit) {
    const afterHotspot4 = `${String(firstVisit.hotspot_end_time.getUTCHours()).padStart(2, '0')}:${String(firstVisit.hotspot_end_time.getUTCMinutes()).padStart(2, '0')}`;
    console.log(`\nTIMING ANALYSIS:`);
    console.log(`  Hotspot 4 ends at: ${afterHotspot4}`);
    console.log(`  If hotspot 18 were selected next:`);
    console.log(`    - Would need travel time from Chennai to Auroville`);
    console.log(`    - Then visit for ${duration18.getUTCHours()} hours`);
    
    // Estimate: 3 hours travel + 2 hours visit = 5 hours total
    const endHours = firstVisit.hotspot_end_time.getUTCHours() + 5;
    console.log(`    - Estimated end time: ~${endHours}:00`);
    console.log(`    - Route ends at: ${routeEnd}`);
    
    // Check against operating hours
    const thursdayTiming = timing.filter(t => t.hotspot_timing_day === 3); // Thursday
    console.log(`\n  Operating hours on Thursday (day 3):`);
    thursdayTiming.forEach(t => {
      const open = `${String(t.hotspot_start_time.getUTCHours()).padStart(2, '0')}:${String(t.hotspot_start_time.getUTCMinutes()).padStart(2, '0')}`;
      const close = `${String(t.hotspot_end_time.getUTCHours()).padStart(2, '0')}:${String(t.hotspot_end_time.getUTCMinutes()).padStart(2, '0')}`;
      console.log(`    ${open} - ${close}`);
    });
  }

  // Check PHP Route 2 for comparison
  console.log('\n=== PHP ROUTE 179 FOR COMPARISON ===');
  const phpTimeline = await prisma.dvi_itinerary_route_hotspot_details.findMany({
    where: { itinerary_route_ID: 179 },
    orderBy: { hotspot_order: 'asc' }
  });

  phpTimeline.filter(t => t.hotspot_ID > 0 && t.item_type === 4).forEach(t => {
    const start = `${String(t.hotspot_start_time.getUTCHours()).padStart(2, '0')}:${String(t.hotspot_start_time.getUTCMinutes()).padStart(2, '0')}`;
    const end = `${String(t.hotspot_end_time.getUTCHours()).padStart(2, '0')}:${String(t.hotspot_end_time.getUTCMinutes()).padStart(2, '0')}`;
    console.log(`  Hotspot ${t.hotspot_ID}: ${start} - ${end}`);
  });

  console.log('\n=== KEY FINDING ===');
  console.log('PHP visits hotspot 18 at 13:40-15:40 (NOT immediately after hotspot 4)');
  console.log('This suggests hotspot 18 has constraints that prevent earlier visit');
  console.log('\nPOSSIBLE REASONS:');
  console.log('1. Operating hours: Closes at 13:00, reopens at 13:30');
  console.log('   - If visited at 10:34, would end at 12:34 ✅ (within 09:00-13:00)');
  console.log('   - If visited at 13:40, would end at 15:40 ✅ (within 13:30-17:00)');
  console.log('2. Travel time calculation might be different');
  console.log('3. Some other PHP logic we haven\'t replicated');

  await prisma.$disconnect();
}

analyzeHotspot18Timing().catch(console.error);
