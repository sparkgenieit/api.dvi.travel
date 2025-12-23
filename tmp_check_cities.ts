import { PrismaClient } from '@prisma/client';

const prisma = new PrismaClient();

async function main() {
  const cities = await prisma.dvi_cities.findMany({
    where: {
      OR: [
        { name: { contains: 'Pondicherry' } },
        { name: { contains: 'Puducherry' } },
        { name: { contains: 'Trichy' } },
        { name: { contains: 'Thiruchirapalli' } }
      ]
    },
    select: { id: true, name: true, state_id: true }
  });
  console.table(cities);
  await prisma.$disconnect();
}

main();
