const { PrismaClient } = require('@prisma/client');
const prisma = new PrismaClient();

async function checkBufferTime() {
  const setting = await prisma.dvi_global_settings.findFirst({
    where: {
      status: 1,
      deleted: 0
    },
    select: {
      itinerary_common_buffer_time: true
    }
  });

  console.log('Refreshment buffer time setting:');
  console.log(setting);

  await prisma.$disconnect();
}

checkBufferTime().catch(console.error);
