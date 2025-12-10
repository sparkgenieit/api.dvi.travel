const { PrismaClient } = require('@prisma/client');
const prisma = new PrismaClient();

async function analyzeHotspots() {
  try {
    const hotspots = await prisma.$queryRaw`
      SELECT hotspot_ID, hotspot_name, hotspot_priority,
             hotspot_latitude, hotspot_longitude
      FROM dvi_hotspot_place
      WHERE hotspot_ID IN (4, 5)
      AND deleted = 0
    `;

    console.log('\n=== HOTSPOT 4 vs 5 COMPARISON ===\n');
    hotspots.forEach(h => {
      console.log(`ID ${h.hotspot_ID}: ${h.hotspot_name}`);
      console.log(`  Priority: ${h.hotspot_priority}`);
      console.log(`  Location: (${h.hotspot_latitude}, ${h.hotspot_longitude})`);
      console.log('');
    });

    // Get hotspot locations
    const chennaiAirport = { lat: 13.0843007, lon: 80.2704622 };  // Route 1 start
    const chennaiCity = { lat: 12.9811068, lon: 80.159623 };      // Route 2 start

    console.log('=== DISTANCES ===\n');
    console.log('Route 1 starts at: Chennai Airport (13.0843007, 80.2704622)');
    console.log('Route 2 starts at: Chennai City (12.9811068, 80.159623)');
    console.log('');

    hotspots.forEach(h => {
      const dist1 = Math.sqrt(
        Math.pow(Number(h.hotspot_latitude) - chennaiAirport.lat, 2) + 
        Math.pow(Number(h.hotspot_longitude) - chennaiAirport.lon, 2)
      );
      const dist2 = Math.sqrt(
        Math.pow(Number(h.hotspot_latitude) - chennaiCity.lat, 2) + 
        Math.pow(Number(h.hotspot_longitude) - chennaiCity.lon, 2)
      );
      
      console.log(`${h.hotspot_name} (ID ${h.hotspot_ID}):`);
      console.log(`  Distance from Airport: ${dist1.toFixed(4)}`);
      console.log(`  Distance from City: ${dist2.toFixed(4)}`);
      console.log('');
    });

    console.log('=== ROUTE 1 DETAILS ===\n');
    console.log('Route: Chennai Airport → Chennai');
    console.log('Both are "Chennai" locations');
    console.log('PHP selects: Marina Beach (ID 5) only');
    console.log('NestJS selects: Both (ID 4 and 5)');
    console.log('');
    console.log('Hypothesis: PHP may limit hotspots for same-city routes');
    console.log('or PHP sorts differently (by distance or other criteria)');

  } finally {
    await prisma.$disconnect();
  }
}

analyzeHotspots().catch(console.error);
