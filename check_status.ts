
import { PrismaClient } from '@prisma/client';

const prisma = new PrismaClient();

async function main() {
  const statusCounts = await prisma.dvi_stored_locations.groupBy({
    by: ['status'],
    _count: {
      _all: true,
    },
  });
  console.log('Status counts:', JSON.stringify(statusCounts, null, 2));

  const nonActive = await prisma.dvi_stored_locations.count({
    where: {
      deleted: 0,
      status: { not: 1 },
    },
  });
  console.log('Non-active (status != 1) but not deleted:', nonActive);
}

main()
  .catch((e) => {
    console.error(e);
    process.exit(1);
  })
  .finally(async () => {
    await prisma.$disconnect();
  });
