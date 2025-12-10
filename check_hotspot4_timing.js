const { PrismaClient } = require('@prisma/client');
const prisma = new PrismaClient();

async function checkHotspot4Timing() {
  const timing = await prisma.dvi_hotspot_timing.findMany({
    where: { hotspot_ID: 4, deleted: 0, status: 1 },
    orderBy: { hotspot_timing_day: 'asc' }
  });

  console.log('\n=== HOTSPOT 4 (Kapaleeshwarar Temple) TIMINGS ===');
  const days = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
  timing.forEach(t => {
    const start = `${String(t.hotspot_start_time.getUTCHours()).padStart(2, '0')}:${String(t.hotspot_start_time.getUTCMinutes()).padStart(2, '0')}`;
    const end = `${String(t.hotspot_end_time.getUTCHours()).padStart(2, '0')}:${String(t.hotspot_end_time.getUTCMinutes()).padStart(2, '0')}`;
    console.log(`Day ${t.hotspot_timing_day} (${days[t.hotspot_timing_day]}): ${start} - ${end} | closed=${t.hotspot_closed} open_all=${t.hotspot_open_all_time}`);
  });

  await prisma.$disconnect();
}

checkHotspot4Timing();
