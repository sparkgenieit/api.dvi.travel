const { PrismaClient } = require('@prisma/client');
const prisma = new PrismaClient();

async function testCategorization() {
  const routeLocation = "Chennai";
  const nextLocation = "Pondicherry";

  // Get all relevant hotspots
  const hotspots = await prisma.$queryRawUnsafe(`
    SELECT 
      hotspot_ID,
      hotspot_name,
      hotspot_location,
      hotspot_priority
    FROM dvi_hotspot_place
    WHERE hotspot_ID IN (4, 18, 21, 19, 17, 677, 678, 679)
    ORDER BY hotspot_ID
  `);

  console.log('\n=== HOTSPOT CATEGORIZATION FOR Chennai → Pondicherry ===\n');
  console.log(`Route: "${routeLocation}" → "${nextLocation}"\n`);

  const categories = {
    source: [],
    destination: [],
    via: []
  };

  hotspots.forEach(h => {
    const loc = (h.hotspot_location || '').split('|');
    
    let category = 'unknown';
    
    // Check if it's a SOURCE hotspot (starts with route location)
    if (loc[0] && loc[0].includes(routeLocation)) {
      category = 'SOURCE';
      categories.source.push(h);
    }
    // Check if it's a DESTINATION hotspot (starts with next location)
    else if (loc[0] && loc[0].includes(nextLocation) && loc[1] && loc[1].includes(routeLocation)) {
      category = 'DESTINATION';
      categories.destination.push(h);
    }
    // Otherwise it's VIA
    else {
      category = 'VIA';
      categories.via.push(h);
    }

    console.log(`${h.hotspot_ID}: ${h.hotspot_name} (Priority ${h.hotspot_priority})`);
    console.log(`   Location: ${h.hotspot_location}`);
    console.log(`   Category: ${category}`);
    console.log('');
  });

  console.log('=== SUMMARY ===');
  console.log(`SOURCE (${categories.source.length}):`, categories.source.map(h => h.hotspot_ID));
  console.log(`VIA (${categories.via.length}):`, categories.via.map(h => h.hotspot_ID));
  console.log(`DESTINATION (${categories.destination.length}):`, categories.destination.map(h => h.hotspot_ID));

  // Sort each category
  const sortHotspots = (hotspots) => {
    return hotspots.sort((a, b) => {
      const aPriority = Number(a.hotspot_priority ?? 0);
      const bPriority = Number(b.hotspot_priority ?? 0);
      
      if (aPriority === 0 && bPriority !== 0) return 1;
      if (aPriority !== 0 && bPriority === 0) return -1;
      if (aPriority === bPriority) return a.hotspot_ID - b.hotspot_ID;
      return aPriority - bPriority;
    });
  };

  sortHotspots(categories.source);
  sortHotspots(categories.via);
  sortHotspots(categories.destination);

  console.log('\n=== AFTER SORTING (Priority, then ID) ===');
  console.log('SOURCE:', categories.source.map(h => `${h.hotspot_ID}(p${h.hotspot_priority})`));
  console.log('VIA:', categories.via.map(h => `${h.hotspot_ID}(p${h.hotspot_priority})`));
  console.log('DESTINATION:', categories.destination.map(h => `${h.hotspot_ID}(p${h.hotspot_priority})`));

  // For direct=0, order is: SOURCE → DESTINATION → VIA
  const ordered = [...categories.source, ...categories.destination, ...categories.via];
  console.log('\n=== FINAL ORDER (SOURCE → DESTINATION → VIA) ===');
  ordered.forEach((h, i) => {
    console.log(`${i+1}. Hotspot ${h.hotspot_ID}: ${h.hotspot_name} (Priority ${h.hotspot_priority})`);
  });

  await prisma.$disconnect();
}

testCategorization().catch(console.error);
