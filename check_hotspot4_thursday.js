const { PrismaClient } = require('@prisma/client');
const prisma = new PrismaClient();

async function checkHotspot4Thursday() {
  try {
    const phpDow = 3; // Thursday

    const hours = await prisma.$queryRaw`
      SELECT 
        TIME_FORMAT(hotspot_start_time, '%H:%i:%s') as start_time,
        TIME_FORMAT(hotspot_end_time, '%H:%i:%s') as end_time
      FROM dvi_hotspot_timing
      WHERE hotspot_ID = 4
      AND hotspot_timing_day = ${phpDow}
      AND deleted = 0
      ORDER BY hotspot_start_time
    `;

    console.log('\n=== HOTSPOT 4 OPERATING HOURS (Thursday - Day 3) ===\n');
    if (hours.length === 0) {
      console.log('❌ NO OPERATING HOURS - CLOSED ON THURSDAY!');
    } else {
      hours.forEach((h, i) => {
        console.log(`  Slot ${i+1}: ${h.start_time} - ${h.end_time}`);
      });
    }

    console.log('\n=== PHP ROUTE 1 (Thursday) ===');
    console.log('Refreshment: 16:30-17:30');
    console.log('First hotspot visit would start: ~17:30-18:00');
    console.log('');

    if (hours.length === 0) {
      console.log('✅ Hotspot 4 is CLOSED on Thursday!');
      console.log('   PHP correctly rejected it');
      console.log('   Only hotspot 5 was added');
    } else {
      const visit_start = '18:00:00';
      let matches = false;
      hours.forEach((h, i) => {
        if (visit_start >= h.start_time && visit_start < h.end_time) {
          console.log(`Hotspot 4 IS open at ${visit_start}`);
          matches = true;
        }
      });
      if (!matches) {
        console.log(`✅ Hotspot 4 is CLOSED at 18:00 on Thursday`);
      }
    }

  } finally {
    await prisma.$disconnect();
  }
}

checkHotspot4Thursday().catch(console.error);
