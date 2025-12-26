const { PrismaClient } = require('@prisma/client');

const prisma = new PrismaClient();

async function main() {
  try {
    console.log('Checking HotspotDistanceCache...');
    
    const count = await prisma.hotspotDistanceCache.count();
    console.log(`Total cache rows: ${count}`);
    
    const rows = await prisma.hotspotDistanceCache.findMany({
      take: 5,
      orderBy: { createdAt: 'desc' }
    });
    
    console.log('Latest cache rows:');
    rows.forEach(row => {
      console.log(`  ${row.fromHotspotId} → ${row.toHotspotId} (type ${row.travelLocationType}): ${row.distanceKm} km`);
    });
    
  } catch (err) {
    console.error('Error:', err.message);
  } finally {
    await prisma.$disconnect();
  }
}

main();
