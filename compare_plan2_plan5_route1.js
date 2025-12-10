const { PrismaClient } = require('@prisma/client');
const prisma = new PrismaClient();

async function comparePlan2and5Route1() {
  try {
    // PHP Plan 2 Route 1
    const php = await prisma.$queryRaw`
      SELECT itinerary_route_ID, location_name, next_visiting_location,
             itinerary_route_date, route_start_time, route_end_time
      FROM dvi_itinerary_route_details
      WHERE itinerary_plan_ID = 2 AND deleted = 0
      ORDER BY itinerary_route_date
      LIMIT 1
    `;

    // NestJS Plan 5 Route 1
    const nestjs = await prisma.$queryRaw`
      SELECT itinerary_route_ID, location_name, next_visiting_location,
             itinerary_route_date, route_start_time, route_end_time
      FROM dvi_itinerary_route_details
      WHERE itinerary_plan_ID = 5 AND deleted = 0
      ORDER BY itinerary_route_date
      LIMIT 1
    `;

    const p = php[0];
    const n = nestjs[0];

    const phpDate = new Date(p.itinerary_route_date);
    const nestDate = new Date(n.itinerary_route_date);

    const phpDow = (phpDate.getDay() + 6) % 7;
    const nestDow = (nestDate.getDay() + 6) % 7;

    console.log('\n=== PHP PLAN 2 ROUTE 1 ===');
    console.log(`Route ID: ${p.itinerary_route_ID}`);
    console.log(`Date: ${phpDate.toDateString()} (${phpDate.toLocaleDateString('en-US', { weekday: 'long'})})`);
    console.log(`PHP day-of-week: ${phpDow}`);
    console.log(`Start: ${p.route_start_time}, End: ${p.route_end_time}`);

    console.log('\n=== NESTJS PLAN 5 ROUTE 1 ===');
    console.log(`Route ID: ${n.itinerary_route_ID}`);
    console.log(`Date: ${nestDate.toDateString()} (${nestDate.toLocaleDateString('en-US', { weekday: 'long'})})`);
    console.log(`PHP day-of-week: ${nestDow}`);
    console.log(`Start: ${n.route_start_time}, End: ${n.route_end_time}`);

    if (phpDow !== nestDow) {
      console.log('\n❌ DIFFERENT DAYS OF WEEK!');
      console.log('   Operating hours will be different!');
    } else {
      console.log('\n✅ Same day of week');
    }

    // Get PHP Route 1 hotspots
    const phpHotspots = await prisma.$queryRaw`
      SELECT hotspot_ID FROM dvi_itinerary_route_hotspot_details
      WHERE itinerary_route_ID = ${p.itinerary_route_ID}
      AND item_type = 4 AND deleted = 0
      ORDER BY hotspot_order
    `;

    const nestHotspots = await prisma.$queryRaw`
      SELECT hotspot_ID FROM dvi_itinerary_route_hotspot_details
      WHERE itinerary_route_ID = ${n.itinerary_route_ID}
      AND item_type = 4 AND deleted = 0
      ORDER BY hotspot_order
    `;

    console.log('\n=== HOTSPOTS ===');
    console.log(`PHP: [${phpHotspots.map(h => h.hotspot_ID).join(', ')}]`);
    console.log(`NestJS: [${nestHotspots.map(h => h.hotspot_ID).join(', ')}]`);

  } finally {
    await prisma.$disconnect();
  }
}

comparePlan2and5Route1().catch(console.error);
