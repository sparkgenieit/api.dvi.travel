import { PrismaClient } from '@prisma/client';

const prisma = new PrismaClient();

async function main() {
  const planId = 33977;
  const details = await prisma.dvi_itinerary_route_hotspot_details.findMany({
    where: { itinerary_plan_ID: planId },
    orderBy: { itinerary_route_ID: 'asc' },
    select: {
      itinerary_route_ID: true,
      hotspot_ID: true,
    }
  });
  console.log('Hotspots in Plan 33977:', details);
}

main().catch(console.error).finally(() => prisma.$disconnect());
