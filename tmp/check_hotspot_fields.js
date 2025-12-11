const { PrismaClient } = require('@prisma/client');
const prisma = new PrismaClient();

async function checkHotspotFields() {
  const hotspot = await prisma.dvi_hotspot_place.findFirst({
    where: { hotspot_ID: 20 }
  });

  console.log('\nFields returned for hotspot 20:');
  console.log(hotspot);
  console.log('\nhotspot_priority:', hotspot.hotspot_priority);
  console.log('hotspot_rating:', hotspot.hotspot_rating);

  await prisma.$disconnect();
}

checkHotspotFields();
