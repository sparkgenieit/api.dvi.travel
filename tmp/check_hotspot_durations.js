const { PrismaClient } = require('@prisma/client');
const prisma = new PrismaClient();

async function main() {
  const h18 = await prisma.dvi_hotspot_place.findUnique({
    where: { hotspot_ID: 18 },
    select: {
      hotspot_ID: true,
      hotspot_name: true,
      hotspot_duration: true,
    },
  });

  const h4 = await prisma.dvi_hotspot_place.findUnique({
    where: { hotspot_ID: 4 },
    select: {
      hotspot_ID: true,
      hotspot_name: true,
      hotspot_duration: true,
    },
  });

  console.log('\n=== Hotspot Durations ===');
  console.log('Hotspot 4:', h4.hotspot_name);
  console.log('  Duration:', h4.hotspot_duration?.toISOString().substring(11, 19));
  
  console.log('\nHotspot 18:', h18.hotspot_name);
  console.log('  Duration:', h18.hotspot_duration?.toISOString().substring(11, 19));

  await prisma.$disconnect();
}

main();
