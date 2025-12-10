const { PrismaClient } = require('@prisma/client');
const prisma = new PrismaClient();

async function main() {
  const hotspot4 = await prisma.dvi_hotspot_place.findUnique({
    where: { hotspot_ID: 4 },
  });

  console.log('\n=== Hotspot 4 Details ===');
  console.log('Location:', hotspot4.hotspot_location);
  console.log('Priority:', hotspot4.hotspot_priority);
  console.log('Name:', hotspot4.hotspot_name);

  const hotspot5 = await prisma.dvi_hotspot_place.findUnique({
    where: { hotspot_ID: 5 },
  });

  console.log('\n=== Hotspot 5 Details ===');
  console.log('Location:', hotspot5.hotspot_location);
  console.log('Priority:', hotspot5.hotspot_priority);
  console.log('Name:', hotspot5.hotspot_name);

  await prisma.$disconnect();
}

main();
