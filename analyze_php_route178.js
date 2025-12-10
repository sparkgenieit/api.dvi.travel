const { PrismaClient } = require('@prisma/client');
const prisma = new PrismaClient();

async function analyzePHPRoute178() {
  try {
    const timeline = await prisma.$queryRaw`
      SELECT 
        item_type, hotspot_ID, hotspot_order,
        TIME_FORMAT(hotspot_start_time, '%H:%i:%s') as start_time,
        TIME_FORMAT(hotspot_end_time, '%H:%i:%s') as end_time
      FROM dvi_itinerary_route_hotspot_details
      WHERE itinerary_route_ID = 178
      AND deleted = 0
      ORDER BY hotspot_order
    `;

    console.log('\n=== PHP ROUTE 178 TIMELINE ===\n');
    const types = {1: 'Refresh', 2: 'Hotel', 3: 'Travel', 4: 'Stay', 5: 'Parking', 6: 'Hotel'};
    timeline.forEach(t => {
      console.log(`Type ${t.item_type} (${types[t.item_type]}): Hotspot ${t.hotspot_ID || 0}`);
      console.log(`  Time: ${t.start_time} - ${t.end_time}`);
    });

    console.log('\n=== ANALYSIS ===');
    console.log('First visit after refreshment would start when?');
    const refresh = timeline.find(t => t.item_type === 1);
    if (refresh) {
      console.log(`Refreshment ends: ${refresh.end_time}`);
      console.log('Next hotspot should start around this time');
    }

  } finally {
    await prisma.$disconnect();
  }
}

analyzePHPRoute178().catch(console.error);
