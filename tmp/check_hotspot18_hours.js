const { PrismaClient } = require('@prisma/client');
const prisma = new PrismaClient();

async function main() {
  const times = await prisma.dvi_hotspot_timing.findMany({
    where: { hotspot_ID: 18, deleted: 0, status: 1 },
    orderBy: { hotspot_start_time: 'asc' },
  });

  console.log('\n=== Hotspot 18 Operating Hours ===');
  times.forEach(t => {
    console.log(`Day ${t.hotspot_timing_day}: ${t.hotspot_start_time?.toISOString().substring(11, 19)} - ${t.hotspot_end_time?.toISOString().substring(11, 19)} (open_all_time=${t.hotspot_open_all_time})`);
  });

  await prisma.$disconnect();
}

main();
