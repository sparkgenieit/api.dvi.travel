const { PrismaClient } = require('@prisma/client');
const prisma = new PrismaClient();

async function checkPriorities() {
  const hotspots = await prisma.dvi_hotspot_place.findMany({
    where: {
      hotspot_ID: { in: [18, 25, 16, 23, 20] }
    },
    select: {
      hotspot_ID: true,
      hotspot_name: true,
      hotspot_priority: true,
      hotspot_location: true
    }
  });
  
  hotspots.sort((a, b) => a.hotspot_ID - b.hotspot_ID);
  
  console.log('\nHotspot Priorities:\n');
  hotspots.forEach(h => {
    console.log(`ID ${h.hotspot_ID}: ${h.hotspot_name}`);
    console.log(`  Priority: ${h.hotspot_priority}`);
    console.log(`  Location: ${h.hotspot_location}\n`);
  });
  
  await prisma.$disconnect();
}

checkPriorities();
