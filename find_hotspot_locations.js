const { PrismaClient } = require('@prisma/client');
const prisma = new PrismaClient();

async function findHotspotLocations() {
  const hotspots = await prisma.$queryRawUnsafe(`
    SELECT hotspot_ID, hotspot_name, hotspot_location
    FROM dvi_hotspot_place
    WHERE hotspot_ID IN (4, 18, 21, 19, 17, 677, 678, 679)
    ORDER BY hotspot_ID
  `);

  console.log('\n=== HOTSPOT LOCATIONS ===\n');
  hotspots.forEach(h => {
    console.log(`${h.hotspot_ID}: ${h.hotspot_name}`);
    console.log(`   ${h.hotspot_location}`);
    console.log('');
  });

  // Find unique location patterns
  const locations = [...new Set(hotspots.map(h => h.hotspot_location))];
  console.log('=== UNIQUE LOCATION PATTERNS ===');
  locations.forEach(l => console.log(l));

  await prisma.$disconnect();
}

findHotspotLocations().catch(console.error);
