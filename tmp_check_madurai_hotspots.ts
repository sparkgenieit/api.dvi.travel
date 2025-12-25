
import { PrismaClient } from '@prisma/client';

const prisma = new PrismaClient();

async function checkMaduraiHotspots() {
  const maduraiCityId = 35; // I recall Madurai is 35 from previous tasks
  console.log(`Checking hotspots for Madurai (City ID: ${maduraiCityId})`);

  const hotspots = await prisma.dvi_hotspot_place.findMany({
    where: { 
      hotspot_location: { contains: 'Madurai' },
      status: 1,
      deleted: 0
    },
    select: {
      hotspot_ID: true,
      hotspot_name: true,
      hotspot_priority: true
    },
    orderBy: { hotspot_priority: 'asc' }
  });

  console.log("ID | Priority | Name");
  console.log("----------------------");
  hotspots.forEach(h => {
    console.log(`${String(h.hotspot_ID).padStart(5)} | ${String(h.hotspot_priority).padStart(8)} | ${h.hotspot_name}`);
  });
}

checkMaduraiHotspots()
  .catch(e => console.error(e))
  .finally(() => prisma.$disconnect());
