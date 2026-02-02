import { PrismaClient } from '@prisma/client';

const prisma = new PrismaClient();

async function checkRoutes() {
  console.log('\n═══════════════════════════════════════════════════════════════');
  console.log('📍 PLAN 17 ROUTES');
  console.log('═══════════════════════════════════════════════════════════════\n');

  const routes = await prisma.dvi_itinerary_route_details.findMany({
    where: { itinerary_plan_ID: 17 },
    orderBy: { itinerary_route_date: 'asc' },
  });

  for (const route of routes) {
    console.log(`Route ${route.itinerary_route_ID}:`);
    console.log(`  Location: ${route.location_name} → ${route.next_visiting_location}`);
    console.log(`  Date: ${route.itinerary_route_date}`);
    console.log('');
  }

  // Find which route is IN Rameswaram
  const rameswaramRoute = routes.find(r => 
    String(r.location_name).toLowerCase().includes('rameswaram')
  );

  if (rameswaramRoute) {
    console.log(`✅ Route IN Rameswaram: Route ${rameswaramRoute.itinerary_route_ID}`);
    console.log(`   Try adding hotspot 41 to this route instead!\n`);
  }

  await prisma.$disconnect();
}

checkRoutes();
