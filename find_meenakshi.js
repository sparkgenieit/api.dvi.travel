const { PrismaClient } = require('@prisma/client');
const prisma = new PrismaClient();

async function findMeenakshi() {
  try {
    const hotspots = await prisma.dvi_hotspot_place.findMany({
      where: {
        hotspot_name: { contains: 'Meenakshi' },
        deleted: 0,
      },
    });

    console.log('\n=== Meenakshi Temple Hotspots ===\n');
    
    if (hotspots.length === 0) {
      console.log('No Meenakshi temple found!');
      
      // Try searching for Madurai temples
      const maduraiHotspots = await prisma.dvi_hotspot_place.findMany({
        where: {
          hotspot_location_id: 16, // Madurai location ID
          deleted: 0,
        },
        orderBy: { hotspot_priority: 'asc' },
      });
      
      console.log('\n=== All Madurai Hotspots ===\n');
      maduraiHotspots.forEach(h => {
        console.log(`ID: ${h.hotspot_ID}, Priority: ${h.hotspot_priority}, Name: ${h.hotspot_name}`);
      });
    } else {
      hotspots.forEach(h => {
        console.log(`ID: ${h.hotspot_ID}`);
        console.log(`Name: ${h.hotspot_name}`);
        console.log(`Priority: ${h.hotspot_priority}`);
        console.log(`Location ID: ${h.hotspot_location_id}`);
        console.log(`Status: ${h.status}`);
        console.log('');
      });
    }

    await prisma.$disconnect();
  } catch (error) {
    console.error('Error:', error);
    await prisma.$disconnect();
  }
}

findMeenakshi();
