const { PrismaClient } = require('@prisma/client');
const prisma = new PrismaClient();

async function listHotspotTables() {
  const result = await prisma.$queryRaw`SHOW TABLES LIKE 'dvi_hotspot%'`;
  console.log('Tables:');
  console.log(result);
  await prisma.$disconnect();
}

listHotspotTables();
