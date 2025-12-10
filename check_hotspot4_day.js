const { PrismaClient } = require('@prisma/client');
const prisma = new PrismaClient();

async function checkHotspot4Hours() {
  try {
    // Check what day Route 1 is
    const route = await prisma.$queryRaw`
      SELECT itinerary_route_date FROM dvi_itinerary_route_details
      WHERE itinerary_route_ID = 391
      LIMIT 1
    `;

    const date = new Date(route[0].itinerary_route_date);
    const dayOfWeek = date.toLocaleDateString('en-US', { weekday: 'long' });
    const phpDow = (date.getDay() + 6) % 7; // PHP: Monday=0

    console.log(`\nRoute 391 Date: ${date.toDateString()} (${dayOfWeek})`);
    console.log(`PHP day-of-week: ${phpDow}`);

    // Get hotspot 4 operating hours for this day
    const hours = await prisma.$queryRaw`
      SELECT * FROM dvi_hotspot_timing
      WHERE hotspot_ID = 4
      AND hotspot_timing_day = ${phpDow}
      AND deleted = 0
    `;

    console.log(`\n=== HOTSPOT 4 OPERATING HOURS (Day ${phpDow} - ${dayOfWeek}) ===\n`);
    if (hours.length === 0) {
      console.log('❌ NO OPERATING HOURS DEFINED!');
      console.log('This means hotspot 4 is CLOSED on this day!');
    } else {
      hours.forEach(h => {
        console.log(`  ${h.hotspot_start_time} - ${h.hotspot_end_time}`);
      });
    }

    console.log('\n=== ANALYSIS ===\n');
    console.log('NestJS visit: 18:00-19:00');
    if (hours.length === 0) {
      console.log('❌ Hotspot 4 should be REJECTED (closed on this day)');
    } else {
      console.log('Check if 18:00-19:00 falls within operating hours above');
    }

  } finally {
    await prisma.$disconnect();
  }
}

checkHotspot4Hours().catch(console.error);
