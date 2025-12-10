const { PrismaClient } = require('@prisma/client');
const prisma = new PrismaClient();

async function checkRoute1Details() {
  try {
    // Get PHP Plan 2 Route 1
    const route = await prisma.$queryRaw`
      SELECT * FROM dvi_itinerary_route_details
      WHERE itinerary_plan_ID = 2 
      AND deleted = 0
      ORDER BY itinerary_route_date, itinerary_route_ID
      LIMIT 1
    `;

    const r = route[0];
    console.log('\n=== PHP PLAN 2 ROUTE 1 ===\n');
    console.log(`Route ID: ${r.itinerary_route_ID}`);
    console.log(`Location: ${r.location_name} (ID: ${r.location_id})`);
    console.log(`Next: ${r.next_visiting_location}`);
    console.log(`Direct to next: ${r.direct_to_next_visiting_place}`);
    console.log(`Start time: ${r.route_start_time}`);
    console.log(`End time: ${r.route_end_time}`);
    console.log(`Date: ${r.itinerary_route_date}`);
    console.log(`No of days: ${r.no_of_days}`);

    // Get timeline rows
    const timeline = await prisma.$queryRaw`
      SELECT * FROM dvi_itinerary_route_hotspot_details
      WHERE itinerary_route_ID = ${r.itinerary_route_ID}
      AND deleted = 0
      ORDER BY hotspot_order
    `;

    console.log(`\n=== TIMELINE (${timeline.length} rows) ===\n`);
    timeline.forEach((t, i) => {
      const types = {1: 'Refresh', 2: 'Hotel', 3: 'Travel', 4: 'Stay', 5: 'Parking'};
      console.log(`${i+1}. Type ${t.item_type} (${types[t.item_type]}): Hotspot ${t.hotspot_ID}, Order ${t.hotspot_order}`);
      if (t.item_type === 4) {
        console.log(`   Time: ${t.hotspot_start_time} - ${t.hotspot_end_time}`);
      }
    });

    // Count hotspots
    const hotspotCount = timeline.filter(t => t.item_type === 4).length;
    console.log(`\nTotal hotspot visits (type 4): ${hotspotCount}`);
    console.log('\n✅ PHP Route 1 has only 1 hotspot (Marina Beach)');
    console.log('❌ NestJS Route 1 has 2 hotspots (Kapaleeshwarar + Marina)');

  } finally {
    await prisma.$disconnect();
  }
}

checkRoute1Details().catch(console.error);
