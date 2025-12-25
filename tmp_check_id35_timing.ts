
import { PrismaClient } from '@prisma/client';

const prisma = new PrismaClient();

async function main() {
  const hotspotId = 35;
  const timings = await prisma.dvi_hotspot_timing.findMany({
    where: { hotspot_ID: hotspotId }
  });
  console.log('Timings for Ramanatha Swami Temple (ID 35):');
  console.log(JSON.stringify(timings, null, 2));
}

main().catch(console.error).finally(() => prisma.$disconnect());
