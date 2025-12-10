const { PrismaClient } = require('@prisma/client');
const prisma = new PrismaClient();

async function checkPlan2AllRoutes() {
  try {
    // Get ALL routes in Plan 2
    const routes = await prisma.$queryRaw`
      SELECT itinerary_route_ID, location_name, next_visiting_location, itinerary_route_date
      FROM dvi_itinerary_route_details
      WHERE itinerary_plan_ID = 2 AND deleted = 0
      ORDER BY itinerary_route_date, itinerary_route_ID
    `;

    console.log('\n=== PHP PLAN 2 ALL ROUTES ===\n');
    for (const r of routes) {
      const date = new Date(r.itinerary_route_date);
      console.log(`Route ${r.itinerary_route_ID}: ${r.location_name} → ${r.next_visiting_location}`);
      console.log(`  Date: ${date.toDateString()}`);

      // Get hotspots for this route
      const hotspots = await prisma.$queryRaw`
        SELECT hotspot_ID FROM dvi_itinerary_route_hotspot_details
        WHERE itinerary_route_ID = ${r.itinerary_route_ID}
        AND item_type = 4 AND deleted = 0
        ORDER BY hotspot_order
      `;

      const hotspotIds = hotspots.map(h => h.hotspot_ID);
      console.log(`  Hotspots: [${hotspotIds.join(', ')}]`);
      console.log('');
    }

    console.log('=== ANALYSIS ===\n');
    console.log('Check if hotspot 4 appears in any route before Route 178');

  } finally {
    await prisma.$disconnect();
  }
}

checkPlan2AllRoutes().catch(console.error);
