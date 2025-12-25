
import { PrismaClient } from '@prisma/client';

const prisma = new PrismaClient();

async function main() {
  const route = await (prisma as any).dvi_itinerary_route_details.findUnique({
    where: { itinerary_route_ID: 207372 },
  });
  console.log('Route:', JSON.stringify(route, (key, value) => typeof value === 'bigint' ? value.toString() : value, 2));
}

main().catch(console.error).finally(() => prisma.$disconnect());
