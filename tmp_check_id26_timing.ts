import { PrismaClient } from '@prisma/client';

const prisma = new PrismaClient();

async function main() {
  const timings = await prisma.dvi_hotspot_timing.findMany({
    where: { hotspot_ID: 26 }
  });
  console.log('Timings for Meenakshi Temple (ID 26):', timings);
}

main().catch(console.error).finally(() => prisma.$disconnect());
