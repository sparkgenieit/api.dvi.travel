import { PrismaClient } from '@prisma/client';

const prisma = new PrismaClient();

async function main() {
  const hs = await prisma.dvi_hotspot_place.findUnique({
    where: { hotspot_ID: 26 },
    select: {
      hotspot_ID: true,
      hotspot_name: true,
      city_ID: true,
      hotspot_priority: true,
    }
  });
  console.log('Meenakshi Temple:', hs);

  const maduraiHotspots = await prisma.dvi_hotspot_place.findMany({
    where: { city_ID: 11 },
    orderBy: { hotspot_priority: 'asc' },
    select: {
      hotspot_ID: true,
      hotspot_name: true,
      hotspot_priority: true,
    }
  });
  console.log('Madurai Hotspots:', maduraiHotspots);
}

main().catch(console.error).finally(() => prisma.$disconnect());
