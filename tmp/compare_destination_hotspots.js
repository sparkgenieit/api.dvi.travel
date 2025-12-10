const { PrismaClient } = require('@prisma/client');
const prisma = new PrismaClient();

async function main() {
  const hotspotIds = [19, 17, 25, 678];
  
  console.log('\n=== Hotspot Comparison ===');
  for (const id of hotspotIds) {
    const h = await prisma.dvi_hotspot_place.findUnique({
      where: { hotspot_ID: id },
      select: {
        hotspot_ID: true,
        hotspot_name: true,
        hotspot_location: true,
        hotspot_priority: true,
      },
    });
    console.log(`\nHotspot ${id}: ${h.hotspot_name}`);
    console.log(`  Location: ${h.hotspot_location}`);
    console.log(`  Priority: ${h.hotspot_priority}`);
  }

  await prisma.$disconnect();
}

main();
