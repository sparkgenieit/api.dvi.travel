import { PrismaClient } from '@prisma/client';

const prisma = new PrismaClient();

async function main() {
  const route = await prisma.dvi_itinerary_route_details.findUnique({
    where: { itinerary_route_ID: 207430 }
  });
  console.log('Route 207430:', route);
}

main().catch(console.error).finally(() => prisma.$disconnect());
