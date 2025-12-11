const { PrismaClient } = require('@prisma/client');
const prisma = new PrismaClient();

async function analyzeRoute3Hotspots() {
  console.log('\n=== ROUTE 3 HOTSPOT ANALYSIS ===\n');
  
  const hotspotIds = [16, 20, 23, 24];
  
  for (const id of hotspotIds) {
    const h = await prisma.dvi_hotspot_place.findUnique({
      where: { hotspot_ID: id }
    });
    
    if (h) {
      console.log(`Hotspot ${id}: ${h.hotspot_name}`);
      console.log(`  Location: ${h.hotspot_location}`);
      console.log(`  Priority: ${h.hotspot_priority || 0}`);
      console.log(`  Coordinates: (${h.hotspot_latitude}, ${h.hotspot_longitude})`);
      console.log('');
    }
  }
  
  console.log('Expected order by PHP: 16 (priority 1), 23 (priority 3), 20 (priority 5)');
  console.log('NestJS order: 20 (priority 5), 16 (priority 1), 23 (priority 3), 24 (priority 0)');
  console.log('\nThis suggests distance is being used for sorting when priorities differ.');
  
  await prisma.$disconnect();
}

analyzeRoute3Hotspots();
