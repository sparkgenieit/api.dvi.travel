import { PrismaClient } from '@prisma/client';

const prisma = new PrismaClient();

async function main() {
  const allHotspots = await prisma.dvi_hotspot_place.findMany({
    where: { deleted: 0, status: 1 },
    select: {
      hotspot_ID: true,
      hotspot_name: true,
      hotspot_priority: true,
    }
  });
  console.log('Total Hotspots:', allHotspots.length);
  const id26 = allHotspots.find(h => h.hotspot_ID === 26);
  console.log('ID 26:', id26);
}

main().catch(console.error).finally(() => prisma.$disconnect());
