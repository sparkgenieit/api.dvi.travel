import { PrismaClient } from '@prisma/client';

const prisma = new PrismaClient();

async function main() {
  const hs = await prisma.dvi_hotspot_place.findUnique({
    where: { hotspot_ID: 26 },
    select: {
      hotspot_ID: true,
      hotspot_name: true,
      status: true,
      deleted: true,
    }
  });
  console.log('Meenakshi Temple:', hs);
}

main().catch(console.error).finally(() => prisma.$disconnect());
