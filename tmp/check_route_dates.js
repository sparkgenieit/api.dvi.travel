const { PrismaClient } = require('@prisma/client');
const prisma = new PrismaClient();

async function checkRouteDates() {
  console.log('=== PLAN 2 ROUTES ===\n');
  const p2Routes = await prisma.dvi_itinerary_route_details.findMany({
    where: { itinerary_plan_ID: 2 },
    select: {
      itinerary_route_ID: true,
      itinerary_route_date: true,
      location_name: true,
      no_of_days: true,
    },
    orderBy: { itinerary_route_date: 'asc' }
  });
  
  p2Routes.forEach(r => {
    const date = new Date(r.itinerary_route_date);
    console.log(`Route ${r.itinerary_route_ID}: ${date.toISOString().split('T')[0]} - ${r.location_name} (${r.no_of_days} days)`);
  });

  console.log('\n=== PLAN 5 ROUTES ===\n');
  const p5Routes = await prisma.dvi_itinerary_route_details.findMany({
    where: { itinerary_plan_ID: 5 },
    select: {
      itinerary_route_ID: true,
      itinerary_route_date: true,
      location_name: true,
      no_of_days: true,
    },
    orderBy: { itinerary_route_date: 'asc' }
  });
  
  p5Routes.forEach(r => {
    const date = new Date(r.itinerary_route_date);
    console.log(`Route ${r.itinerary_route_ID}: ${date.toISOString().split('T')[0]} - ${r.location_name} (${r.no_of_days} days)`);
  });

  console.log('\n=== COMPARISON ===');
  console.log(`Plan 2: ${p2Routes.length} routes`);
  console.log(`Plan 5: ${p5Routes.length} routes`);
  console.log('\nPlan 2 dates:', p2Routes.map(r => new Date(r.itinerary_route_date).toISOString().split('T')[0]).join(', '));
  console.log('Plan 5 dates:', p5Routes.map(r => new Date(r.itinerary_route_date).toISOString().split('T')[0]).join(', '));
  
  if (p5Routes.length !== p2Routes.length) {
    console.log('\n⚠️ ISSUE: Different number of routes!');
  }
  
  await prisma.$disconnect();
}

checkRouteDates().catch(console.error);
