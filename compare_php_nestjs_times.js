const { PrismaClient } = require('@prisma/client');
const prisma = new PrismaClient();

async function comparePHPvsNestJS() {
  try {
    // PHP Route 178
    const phpTimeline = await prisma.$queryRaw`
      SELECT hotspot_order, item_type, hotspot_ID, hotspot_start_time, hotspot_end_time
      FROM dvi_itinerary_route_hotspot_details
      WHERE itinerary_route_ID = 178
      AND deleted = 0
      ORDER BY hotspot_order
    `;

    console.log('\n=== PHP ROUTE 178 (Route 1) ===\n');
    const types = {1: 'Refresh', 2: 'Hotel', 3: 'Travel', 4: 'Stay', 5: 'Parking', 6: 'Hotel'};
    phpTimeline.forEach(t => {
      const start = new Date(t.hotspot_start_time);
      const end = new Date(t.hotspot_end_time);
      const startTime = `${String(start.getHours()).padStart(2,'0')}:${String(start.getMinutes()).padStart(2,'0')}`;
      const endTime = `${String(end.getHours()).padStart(2,'0')}:${String(end.getMinutes()).padStart(2,'0')}`;
      
      console.log(`${t.hotspot_order}. ${types[t.item_type]} ${t.hotspot_ID ? `(Hotspot ${t.hotspot_ID})` : ''}: ${startTime}-${endTime}`);
    });

    // NestJS Route 388
    const nestjsTimeline = await prisma.$queryRaw`
      SELECT hotspot_order, item_type, hotspot_ID, hotspot_start_time, hotspot_end_time
      FROM dvi_itinerary_route_hotspot_details
      WHERE itinerary_route_ID = 388
      AND deleted = 0
      ORDER BY hotspot_order
    `;

    console.log('\n=== NESTJS ROUTE 388 (Route 1) ===\n');
    nestjsTimeline.forEach(t => {
      const start = new Date(t.hotspot_start_time);
      const end = new Date(t.hotspot_end_time);
      const startTime = `${String(start.getHours()).padStart(2,'0')}:${String(start.getMinutes()).padStart(2,'0')}`;
      const endTime = `${String(end.getHours()).padStart(2,'0')}:${String(end.getMinutes()).padStart(2,'0')}`;
      
      console.log(`${t.hotspot_order}. ${types[t.item_type]} ${t.hotspot_ID ? `(Hotspot ${t.hotspot_ID})` : ''}: ${startTime}-${endTime}`);
    });

    console.log('\n=== COMPARISON ===\n');
    console.log('PHP Route 1:');
    console.log('  - Refreshment: 16:30-17:30');
    console.log('  - Hotspot 5: ~17:30-18:30');
    console.log('');
    console.log('NestJS Route 1:');
    console.log('  - Shows times 5.5 hours LATER (UTC vs IST issue!)');
    console.log('  - Refreshment stored as: 22:00-23:00 (should be 16:30-17:30)');
    console.log('  - Hotspot 4 at 23:30 (should be 18:00)');
    console.log('');
    console.log('ROOT CAUSE: NestJS is storing times in UTC but database expects IST!');

  } finally {
    await prisma.$disconnect();
  }
}

comparePHPvsNestJS().catch(console.error);
