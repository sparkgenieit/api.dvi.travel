const { PrismaClient } = require('@prisma/client');
const prisma = new PrismaClient();

async function checkHotspot4HoursClear() {
  try {
    const phpDow = 2; // Wednesday

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

    console.log('\n=== HOTSPOT 4 OPERATING HOURS (Wednesday) ===\n');
    hours.forEach((h, i) => {
      console.log(`  Slot ${i+1}: ${h.start_time} - ${h.end_time}`);
    });

    console.log('\n=== VISIT TIMING ===\n');
    console.log('NestJS hotspot 4 visit: 18:00:00 - 19:00:00');
    console.log('');
    
    const visit_start = '18:00:00';
    const visit_end = '19:00:00';
    
    let matches = false;
    hours.forEach((h, i) => {
      if (visit_start >= h.start_time && visit_start < h.end_time) {
        console.log(`✅ Visit start (${visit_start}) falls in Slot ${i+1} (${h.start_time}-${h.end_time})`);
        matches = true;
      }
    });

    if (!matches) {
      console.log('❌ Visit start (18:00:00) does NOT fall in any operating hours window!');
      console.log('   Hotspot 4 should be REJECTED!');
    }

  } finally {
    await prisma.$disconnect();
  }
}

checkHotspot4HoursClear().catch(console.error);
