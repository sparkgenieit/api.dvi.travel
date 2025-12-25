const { PrismaClient } = require('@prisma/client');
const prisma = new PrismaClient();

async function main() {
  const t = await prisma.dvi_hotspot_timing.findMany({
    where: { hotspot_ID: 11 }
  });
  console.log(JSON.stringify(t, null, 2));
}

main().finally(() => prisma.$disconnect());
