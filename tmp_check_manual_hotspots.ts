
import { PrismaClient } from '@prisma/client';

const prisma = new PrismaClient();

async function checkManualHotspots() {
  const planId = 33977;
  console.log(`Checking manual hotspots for Plan ${planId}`);

  const segments = await prisma.dvi_itinerary_route_hotspot_details.findMany({
    where: { 
      itinerary_plan_ID: planId,
      hotspot_plan_own_way: 1
    }
  });

  console.log(`Found ${segments.length} manual hotspots.`);
  segments.forEach(s => {
    console.log(`ID: ${s.hotspot_ID}, Route ID: ${s.itinerary_route_ID}`);
  });
}

checkManualHotspots()
  .catch(e => console.error(e))
  .finally(() => prisma.$disconnect());
