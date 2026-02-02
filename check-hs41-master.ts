import { PrismaClient } from '@prisma/client';

const prisma = new PrismaClient();

async function check() {
  try {
    const hs41 = await (prisma as any).dvi_hotspot_place.findUnique({
      where: { hotspot_ID: 41 },
      select: {
        hotspot_ID: true,
        hotspot_name: true,
        hotspot_location: true,
        hotspot_latitude: true,
        hotspot_longitude: true,
        hotspot_duration: true,
        status: true,
        deleted: true,
      },
    });

    console.log('\nHotspot 41 in master table:');
    console.log(JSON.stringify(hs41, null, 2));

  } catch (error) {
    console.error('Error:', error);
  } finally {
    await prisma.$disconnect();
  }
}

check();
