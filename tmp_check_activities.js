
const { PrismaClient } = require('@prisma/client');
const prisma = new PrismaClient();

async function main() {
  try {
    const count = await prisma.dvi_activity.count({ where: { deleted: 0 } });
    console.log(`Active activities count: ${count}`);
    
    const rows = await prisma.dvi_activity.findMany({
      take: 5,
      where: { deleted: 0 },
      select: { activity_id: true, activity_title: true, status: true }
    });
    console.log('Sample rows:', JSON.stringify(rows, null, 2));
  } catch (e) {
    console.error(e);
  } finally {
    await prisma.$disconnect();
  }
}

main();
