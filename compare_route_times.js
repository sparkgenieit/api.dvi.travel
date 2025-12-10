const { PrismaClient } = require('@prisma/client');
const prisma = new PrismaClient();

async function compareRouteStartTimes() {
  console.log('\n=== COMPARING ROUTE START TIMES ===\n');
  
  const plan2Routes = await prisma.dvi_itinerary_route_details.findMany({
    where: { itinerary_plan_ID: 2, deleted: 0, status: 1 },
    orderBy: { itinerary_route_ID: 'asc' },
  });
  
  const plan5Routes = await prisma.dvi_itinerary_route_details.findMany({
    where: { itinerary_plan_ID: 5, deleted: 0, status: 1 },
    orderBy: { itinerary_route_ID: 'asc' },
  });
  
  console.log('=== PLAN 2 (PHP) ROUTES ===');
  plan2Routes.forEach((r, idx) => {
    console.log(`  Route ${idx + 1} (ID ${r.itinerary_route_ID}): ${r.route_start_time} → ${r.route_end_time}`);
  });
  
  console.log('\n=== PLAN 5 (NestJS) ROUTES ===');
  plan5Routes.forEach((r, idx) => {
    console.log(`  Route ${idx + 1} (ID ${r.itinerary_route_ID}): ${r.route_start_time} → ${r.route_end_time}`);
  });
  
  // Check if route_start_time is being used correctly
  const route2PHP = plan2Routes[1];
  const route2Nest = plan5Routes[1];
  
  console.log('\n=== ROUTE 2 COMPARISON ===');
  console.log(`PHP Route ${route2PHP.itinerary_route_ID}:`);
  console.log(`  route_start_time: ${route2PHP.route_start_time}`);
  console.log(`  route_end_time: ${route2PHP.route_end_time}`);
  console.log(`  First row start: 13:30:00 (from timeline)`);
  
  console.log(`\nNestJS Route ${route2Nest.itinerary_route_ID}:`);
  console.log(`  route_start_time: ${route2Nest.route_start_time}`);
  console.log(`  route_end_time: ${route2Nest.route_end_time}`);
  console.log(`  First row start: 13:30:00 (from timeline)`);
  console.log(`  Log says: Route starts at 08:00:00 ← MISMATCH!`);
  
  await prisma.$disconnect();
}

compareRouteStartTimes().catch(console.error);
