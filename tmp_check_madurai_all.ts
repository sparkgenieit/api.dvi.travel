import { PrismaClient } from '@prisma/client';

const prisma = new PrismaClient();

async function main() {
  const maduraiHotspots = await prisma.dvi_hotspot_place.findMany({
    where: {
        hotspot_location: { contains: 'Madurai' },
        deleted: 0,
        status: 1
    },
    orderBy: { hotspot_priority: 'asc' },
    select: {
      hotspot_ID: true,
      hotspot_name: true,
      hotspot_priority: true,
      hotspot_open_time: true,
      hotspot_close_time: true,
      hotspot_open_time2: true,
      hotspot_close_time2: true,
    }
  });
  console.log('Madurai Hotspots:', maduraiHotspots);
}

main().catch(console.error).finally(() => prisma.$disconnect());
