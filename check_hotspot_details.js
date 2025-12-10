const { PrismaClient } = require('@prisma/client');
const prisma = new PrismaClient();

async function checkHotspotDetails() {
  try {
    const hotspots = await prisma.dvi_hotspot_place.findMany({
      where: {
        hotspot_ID: { in: [4, 5, 544] }
      },
      select: {
        hotspot_ID: true,
        hotspot_name: true,
        hotspot_priority: true,
        hotspot_duration: true,
        hotspot_location: true
      }
    });
    
    console.log('=== DESTINATION HOTSPOTS FOR CHENNAI ===\n');
    hotspots.forEach(h => {
      console.log(`ID ${h.hotspot_ID}: ${h.hotspot_name}`);
      console.log(`  Priority: ${h.hotspot_priority}`);
      console.log(`  Stay Time: ${h.hotspot_duration} minutes`);
      console.log(`  Location: ${h.hotspot_location}`);
      console.log('');
    });
    
    await prisma.$disconnect();
  } catch (error) {
    console.error('Error:', error);
    await prisma.$disconnect();
  }
}

checkHotspotDetails();
