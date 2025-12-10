const { PrismaClient } = require('@prisma/client');
const prisma = new PrismaClient();

async function debugRoute401() {
  const timeline = await prisma.dvi_itinerary_route_hotspot_details.findMany({
    where: { itinerary_route_ID: 401 },
    orderBy: { hotspot_order: 'asc' }
  });

  console.log('\n=== ROUTE 401 TIMELINE ===');
  timeline.forEach((h, i) => {
    const start = h.hotspot_start_time;
    const end = h.hotspot_end_time;
    const startStr = `${String(start.getUTCHours()).padStart(2, '0')}:${String(start.getUTCMinutes()).padStart(2, '0')}`;
    const endStr = `${String(end.getUTCHours()).padStart(2, '0')}:${String(end.getUTCMinutes()).padStart(2, '0')}`;
    console.log(`${i+1}. [Type ${h.item_type}] ${startStr}-${endStr}: Hotspot ${h.hotspot_ID}`);
  });

  await prisma.$disconnect();
}

debugRoute401().catch(console.error);
