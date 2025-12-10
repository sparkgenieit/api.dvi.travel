const { PrismaClient } = require('@prisma/client');
const prisma = new PrismaClient();

async function checkPHPRoute1Timing() {
  try {
    const route = await prisma.$queryRaw`
      SELECT * FROM dvi_itinerary_route_details
      WHERE itinerary_plan_ID = 2 
      AND deleted = 0
      ORDER BY itinerary_route_date
      LIMIT 1
    `;

    const r = route[0];
    console.log('\n=== PHP PLAN 2 ROUTE 1 ===\n');
    console.log(`Location: ${r.location_name} → ${r.next_visiting_location}`);
    console.log(`Direct: ${r.direct_to_next_visiting_place}`);
    console.log(`Start time: ${r.route_start_time}`);
    console.log(`End time: ${r.route_end_time}`);
    console.log('');

    // Check hotspot 4 operating hours on day 2 (Tuesday)
    const hotspot4Hours = await prisma.$queryRaw`
      SELECT * FROM dvi_hotspot_timing
      WHERE hotspot_ID = 4
      AND hotspot_timing_day = 2
      AND deleted = 0
    `;

    console.log('=== HOTSPOT 4 (Kapaleeshwarar Temple) OPERATING HOURS - Tuesday (Day 2) ===\n');
    hotspot4Hours.forEach(h => {
      console.log(`  ${h.hotspot_start_time} - ${h.hotspot_end_time}`);
    });

    // Check hotspot 5 operating hours
    const hotspot5Hours = await prisma.$queryRaw`
      SELECT * FROM dvi_hotspot_timing
      WHERE hotspot_ID = 5
      AND hotspot_timing_day = 2
      AND deleted = 0
    `;

    console.log('\n=== HOTSPOT 5 (Marina Beach) OPERATING HOURS - Tuesday (Day 2) ===\n');
    hotspot5Hours.forEach(h => {
      console.log(`  ${h.hotspot_start_time} - ${h.hotspot_end_time}`);
    });

    console.log('\n=== ANALYSIS ===\n');
    console.log(`Route 1 starts: ${r.route_start_time}`);
    console.log(`Route 1 ends: ${r.route_end_time}`);
    console.log('');
    console.log('If route starts at 17:30:');
    console.log('  - Refreshment break: 17:30-18:30');
    console.log('  - First hotspot visit would start around: 18:30+');
    console.log('');
    console.log('Check if hotspot 4 is open at 18:30+ on Tuesday');
    console.log('Check if hotspot 5 is open at 18:30+ on Tuesday');

  } finally {
    await prisma.$disconnect();
  }
}

checkPHPRoute1Timing().catch(console.error);
