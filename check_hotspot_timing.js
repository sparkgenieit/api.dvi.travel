const { PrismaClient } = require('@prisma/client');
const prisma = new PrismaClient();

async function checkHotspotTiming() {
  const timings = await prisma.dvi_hotspot_timing.findMany({
    where: {
      hotspot_ID: { in: [4, 17, 18, 19, 21, 544, 678] }
    },
    orderBy: [
      { hotspot_ID: 'asc' },
      { hotspot_timing_day: 'asc' }
    ]
  });

  console.log('Hotspot Operating Hours:');
  console.log('========================\n');
  
  const grouped = {};
  timings.forEach(t => {
    if (!grouped[t.hotspot_ID]) {
      grouped[t.hotspot_ID] = [];
    }
    grouped[t.hotspot_ID].push(t);
  });

  const dayNames = ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'];
  
  for (const [hotspotId, times] of Object.entries(grouped)) {
    console.log(`Hotspot ${hotspotId}:`);
    times.forEach(t => {
      console.log(`  Day ${t.hotspot_timing_day} (${dayNames[t.hotspot_timing_day]}): ${t.hotspot_start_time || 'NULL'} - ${t.hotspot_end_time || 'NULL'} ${t.hotspot_open_all_time ? '(24h)' : ''}`);
    });
    console.log();
  }

  await prisma.$disconnect();
}

checkHotspotTiming().catch(console.error);
