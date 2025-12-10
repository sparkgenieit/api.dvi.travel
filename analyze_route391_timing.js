const { PrismaClient } = require('@prisma/client');
const prisma = new PrismaClient();

async function analyzeRoute391Timing() {
  try {
    // Get NestJS Route 391 timeline
    const timeline = await prisma.$queryRaw`
      SELECT * FROM dvi_itinerary_route_hotspot_details
      WHERE itinerary_route_ID = 391
      AND deleted = 0
      ORDER BY hotspot_order
    `;

    console.log('\n=== NESTJS ROUTE 391 (Route 1) TIMELINE ===\n');
    timeline.forEach((t, i) => {
      const types = {1: 'Refresh', 2: 'Hotel', 3: 'Travel', 4: 'Stay', 5: 'Parking'};
      console.log(`${i+1}. Type ${t.item_type} (${types[t.item_type]}): Hotspot ${t.hotspot_ID}`);
      console.log(`   Time: ${t.hotspot_start_time} - ${t.hotspot_end_time}`);
    });

    console.log('\n=== ANALYSIS ===\n');
    console.log('If refreshment is at correct time (16:30-17:30):');
    console.log('  - First hotspot should start around 17:30');
    console.log('  - Hotspot 4 closes at 17:30, so should be rejected');
    console.log('  - Only hotspot 5 should be added');

  } finally {
    await prisma.$disconnect();
  }
}

analyzeRoute391Timing().catch(console.error);
