import { PrismaClient } from '@prisma/client';
const prisma = new PrismaClient();

async function main() {
  const ids = [4, 5, 11, 6, 7, 294];
  const hotspots = await prisma.dvi_hotspot_place.findMany({
    where: { hotspot_ID: { in: ids } },
    select: {
      hotspot_ID: true,
      hotspot_name: true,
      hotspot_priority: true
    }
  });
  console.log(JSON.stringify(hotspots, null, 2));
}

main()
  .catch(console.error)
  .finally(() => prisma.$disconnect());
