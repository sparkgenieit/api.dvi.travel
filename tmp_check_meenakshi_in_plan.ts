
import { PrismaClient } from '@prisma/client';

const prisma = new PrismaClient();

async function checkMeenakshiInPlan() {
  const planId = 33977;
  const hotspotId = 26;
  console.log(`Checking if Meenakshi Temple (ID: ${hotspotId}) is in Plan ${planId}`);

  const segments = await prisma.dvi_itinerary_route_hotspot_details.findMany({
    where: { 
      itinerary_plan_ID: planId,
      hotspot_ID: hotspotId
    }
  });

  console.log(`Found ${segments.length} segments.`);
  segments.forEach(s => {
    console.log(`Route ID: ${s.itinerary_route_ID}, Order: ${s.hotspot_order}, Status: ${s.status}, Deleted: ${s.deleted}`);
  });
}

checkMeenakshiInPlan()
  .catch(e => console.error(e))
  .finally(() => prisma.$disconnect());
