const { PrismaClient } = require('@prisma/client');
const prisma = new PrismaClient();

async function analyzeRoute388Timing() {
  try {
    // Get Route 388 details
    const route = await prisma.$queryRaw`
      SELECT * FROM dvi_itinerary_route_details
      WHERE itinerary_route_ID = 388
      AND deleted = 0
    `;

    console.log('\n=== NESTJS ROUTE 388 (Route 1) ===\n');
    console.log(`Location: ${route[0].location_name} → ${route[0].next_visiting_location}`);
    console.log(`Start time: ${route[0].route_start_time}`);
    console.log(`End time: ${route[0].route_end_time}`);
    console.log(`Direct: ${route[0].direct_to_next_visiting_place}`);
    console.log('');

    // Get timeline for Route 388
    const timeline = await prisma.$queryRaw`
      SELECT * FROM dvi_itinerary_route_hotspot_details
      WHERE itinerary_route_ID = 388
      AND deleted = 0
      ORDER BY hotspot_order
    `;

    console.log('=== TIMELINE ===\n');
    const types = {1: 'Refresh', 2: 'Hotel', 3: 'Travel', 4: 'Stay', 5: 'Parking'};
    timeline.forEach(t => {
      console.log(`Order ${t.hotspot_order}: Type ${t.item_type} (${types[t.item_type]})`);
      if (t.hotspot_ID) {
        console.log(`  Hotspot ${t.hotspot_ID}`);
      }
      console.log(`  Time: ${t.hotspot_start_time} - ${t.hotspot_end_time}`);
      console.log('');
    });

    // Get hotspot 4 operating hours for day 2 (Tuesday)
    const hotspot4Hours = await prisma.$queryRaw`
      SELECT * FROM dvi_hotspot_timing
      WHERE hotspot_ID = 4
      AND hotspot_timing_day = 2
      AND deleted = 0
    `;

    console.log('=== HOTSPOT 4 OPERATING HOURS (Tuesday) ===\n');
    hotspot4Hours.forEach(h => {
      console.log(`  ${h.hotspot_start_time} - ${h.hotspot_end_time}`);
    });

    console.log('\n=== ANALYSIS ===\n');
    const hotspot4Row = timeline.find(t => t.hotspot_ID === 4 && t.item_type === 4);
    if (hotspot4Row) {
      console.log('✅ Hotspot 4 WAS ADDED by NestJS');
      console.log(`   Visit time: ${hotspot4Row.hotspot_start_time} - ${hotspot4Row.hotspot_end_time}`);
      console.log('');
      console.log('Checking against operating hours:');
      hotspot4Hours.forEach(h => {
        const visitStart = hotspot4Row.hotspot_start_time.toString().substring(0, 8);
        const hoursStart = h.hotspot_start_time.toString().substring(0, 8);
        const hoursEnd = h.hotspot_end_time.toString().substring(0, 8);
        console.log(`   Operating: ${hoursStart} - ${hoursEnd}`);
        console.log(`   Visit starts: ${visitStart}`);
        if (visitStart >= hoursStart && visitStart < hoursEnd) {
          console.log('   ✅ Visit start IS within operating hours');
        } else {
          console.log('   ❌ Visit start NOT within operating hours');
        }
      });
    } else {
      console.log('❌ Hotspot 4 was NOT added');
    }

    // Compare with PHP
    console.log('\n=== PHP COMPARISON ===\n');
    const phpRoute = await prisma.$queryRaw`
      SELECT * FROM dvi_itinerary_route_details
      WHERE itinerary_route_ID = 178
      AND deleted = 0
    `;
    console.log(`PHP Route 178 start: ${phpRoute[0].route_start_time}`);
    console.log(`NestJS Route 388 start: ${route[0].route_start_time}`);
    console.log('');
    console.log('Times should match! If different, that\'s the root cause.');

  } finally {
    await prisma.$disconnect();
  }
}

analyzeRoute388Timing().catch(console.error);
