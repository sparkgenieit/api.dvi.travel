const { PrismaClient } = require('@prisma/client');
const prisma = new PrismaClient();

async function checkHotspotLocations() {
  console.log('\n=== HOTSPOT LOCATION FIELDS ===\n');
  
  const hotspots = [4, 17, 18, 19, 20, 21, 24, 25, 677, 678, 679];
  
  for (const id of hotspots) {
    const h = await prisma.dvi_hotspot_place.findUnique({
      where: { hotspot_ID: id }
    });
    
    if (h) {
      console.log(`ID ${id}: ${h.hotspot_name}`);
      console.log(`  hotspot_location: "${h.hotspot_location}"`);
      console.log(`  Priority: ${h.hotspot_priority || 0}`);
      
      // Parse the location field
      const locations = (h.hotspot_location || '').split('|').map(l => l.trim());
      console.log(`  Locations: [${locations.join(', ')}]`);
      console.log('');
    }
  }
  
  await prisma.$disconnect();
}

checkHotspotLocations();
