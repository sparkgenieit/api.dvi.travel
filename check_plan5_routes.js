const { PrismaClient } = require('@prisma/client');
const prisma = new PrismaClient();

async function checkPlan5Routes() {
  try {
    const routes = await prisma.$queryRaw`
      SELECT itinerary_route_ID, location_name, next_visiting_location, itinerary_route_date
      FROM dvi_itinerary_route_details
      WHERE itinerary_plan_ID = 5
      AND deleted = 0
      ORDER BY itinerary_route_date, itinerary_route_ID
    `;

    console.log('\n=== PLAN 5 ROUTES (Latest Optimization) ===\n');
    routes.forEach((r, i) => {
      console.log(`Route ${i+1} (ID ${r.itinerary_route_ID}): ${r.location_name} → ${r.next_visiting_location}`);
      console.log(`  Date: ${r.itinerary_route_date}`);
      console.log('');
    });

  } finally {
    await prisma.$disconnect();
  }
}

checkPlan5Routes().catch(console.error);
