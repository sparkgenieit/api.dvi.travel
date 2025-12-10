const { PrismaClient } = require('@prisma/client');
const prisma = new PrismaClient();

async function main() {
  const h544 = await prisma.dvi_hotspot_place.findUnique({
    where: { hotspot_ID: 544 },
  });

  console.log('\n=== Hotspot 544 ===');
  console.log('Name:', h544.hotspot_name);
  console.log('Location:', h544.hotspot_location);
  console.log('Priority:', h544.hotspot_priority);
  console.log('Duration:', h544.hotspot_duration?.toISOString().substring(11, 19));

  await prisma.$disconnect();
}

main();
