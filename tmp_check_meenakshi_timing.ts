
import { PrismaClient } from '@prisma/client';

const prisma = new PrismaClient();

async function checkMeenakshiTiming() {
  const hotspotId = 26;
  console.log(`Checking timing for Meenakshi Amman Temple (ID: ${hotspotId})`);

  const timings = await prisma.dvi_hotspot_timing.findMany({
    where: { hotspot_ID: hotspotId }
  });

  console.log("Day | Start | End | Closed");
  console.log("---------------------------");
  timings.forEach(t => {
    const start = t.hotspot_start_time?.toISOString().split('T')[1].substring(0, 5);
    const end = t.hotspot_end_time?.toISOString().split('T')[1].substring(0, 5);
    console.log(`${t.hotspot_timing_day} | ${start} | ${end} | ${t.hotspot_closed}`);
  });
}

checkMeenakshiTiming()
  .catch(e => console.error(e))
  .finally(() => prisma.$disconnect());
