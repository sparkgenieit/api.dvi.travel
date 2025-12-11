const { PrismaClient } = require('@prisma/client');
const prisma = new PrismaClient();

async function analyzeHotspotFiltering() {
  console.log('\n=== HOTSPOT FILTERING ANALYSIS ===\n');
  
  // Get all Pondicherry hotspots
  const pondicherryHotspots = await prisma.dvi_hotspot_place.findMany({
    where: {
      deleted: 0,
      status: 1,
      OR: [
        { hotspot_location: { contains: 'Pondicherry' } },
        { hotspot_location: { contains: 'pondicherry' } }
      ]
    },
    orderBy: { hotspot_priority: 'asc' }
  });
  
  console.log(`Total Pondicherry hotspots: ${pondicherryHotspots.length}\n`);
  
  // Check which ones match the locations we care about
  const sourceHotspots = [];
  const destHotspots = [];
  
  for (const h of pondicherryHotspots) {
    const locations = h.hotspot_location || '';
    const priority = h.hotspot_priority || 0;
    
    // Check if matches "Pondicherry" (source for Route 3)
    if (locations.includes('Pondicherry') && !locations.includes('Airport')) {
      sourceHotspots.push({ ...h, priority });
    }
    
    // Check if matches "Pondicherry Airport" (destination for Route 3)
    if (locations.includes('Pondicherry Airport') || locations.includes('Airport')) {
      destHotspots.push({ ...h, priority });
    }
  }
  
  console.log(`Source hotspots (Pondicherry): ${sourceHotspots.length}`);
  sourceHotspots.forEach(h => {
    console.log(`  ID ${h.hotspot_ID}: "${h.hotspot_name}" - Priority: ${h.priority}`);
  });
  
  console.log(`\nDestination hotspots (Pondicherry Airport): ${destHotspots.length}`);
  destHotspots.forEach(h => {
    console.log(`  ID ${h.hotspot_ID}: "${h.hotspot_name}" - Priority: ${h.priority}`);
  });
  
  // Check hotspots that PHP selected for Route 3
  console.log('\n=== PHP Selected Hotspots (Route 3) ===');
  const phpSelected = [18, 25, 16, 23, 20, 676, 669];
  
  for (const id of phpSelected) {
    const h = await prisma.dvi_hotspot_place.findUnique({
      where: { hotspot_ID: id }
    });
    if (h) {
      console.log(`  ID ${h.hotspot_ID}: "${h.hotspot_name}"`);
      console.log(`    Location: ${h.hotspot_location}`);
      console.log(`    Priority: ${h.hotspot_priority || 0}`);
    }
  }
  
  // Check hotspots that NestJS selected for Route 3
  console.log('\n=== NestJS Selected Hotspots (Route 3) ===');
  const nestSelected = [25, 23, 277, 669];
  
  for (const id of nestSelected) {
    const h = await prisma.dvi_hotspot_place.findUnique({
      where: { hotspot_ID: id }
    });
    if (h) {
      console.log(`  ID ${h.hotspot_ID}: "${h.hotspot_name}"`);
      console.log(`    Location: ${h.hotspot_location}`);
      console.log(`    Priority: ${h.hotspot_priority || 0}`);
    }
  }
  
  await prisma.$disconnect();
}

analyzeHotspotFiltering();
