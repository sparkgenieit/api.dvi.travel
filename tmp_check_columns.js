
const { PrismaClient } = require('@prisma/client');
const prisma = new PrismaClient();

async function main() {
  try {
    const count = await prisma.dvi_hotspot_place.count({
      where: { hotspot_priority: 0 }
    });
    console.log('Count of hotspots with priority 0:', count);
    
    const total = await prisma.dvi_hotspot_place.count();
    console.log('Total hotspots:', total);
  } catch (error) {
    console.error(error);
  } finally {
    await prisma.$disconnect();
  }
}

main();
