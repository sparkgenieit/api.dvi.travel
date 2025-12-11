const { PrismaClient } = require('@prisma/client');
const prisma = new PrismaClient();

async function compareRouteLocations() {
  console.log('=== PLAN 2 ROUTE LOCATIONS ===\n');
  const p2Routes = await prisma.dvi_itinerary_route_details.findMany({
    where: { itinerary_plan_ID: 2 },
    select: {
      itinerary_route_ID: true,
      itinerary_route_date: true,
      location_name: true,
      next_visiting_location: true,
    },
    orderBy: { itinerary_route_ID: 'asc' }
  });
  
  p2Routes.forEach(r => {
    const date = new Date(r.itinerary_route_date).toISOString().split('T')[0];
    console.log(`Route ${r.itinerary_route_ID} (${date}):`);
    console.log(`  FROM: "${r.location_name}"`);
    console.log(`  TO:   "${r.next_visiting_location}"`);
  });

  console.log('\n=== PLAN 5 ROUTE LOCATIONS ===\n');
  const p5Routes = await prisma.dvi_itinerary_route_details.findMany({
    where: { itinerary_plan_ID: 5 },
    select: {
      itinerary_route_ID: true,
      itinerary_route_date: true,
      location_name: true,
      next_visiting_location: true,
    },
    orderBy: { itinerary_route_ID: 'asc' }
  });
  
  p5Routes.forEach(r => {
    const date = new Date(r.itinerary_route_date).toISOString().split('T')[0];
    console.log(`Route ${r.itinerary_route_ID} (${date}):`);
    console.log(`  FROM: "${r.location_name}"`);
    console.log(`  TO:   "${r.next_visiting_location}"`);
  });

  console.log('\n=== HOTEL LOCATIONS ===\n');
  console.log('Plan 2 Hotels:');
  const p2Hotels = await prisma.dvi_itinerary_plan_hotel_details.findMany({
    where: { itinerary_plan_id: 2 },
    select: {
      itinerary_route_id: true,
      itinerary_route_location: true,
      group_type: true,
    },
    distinct: ['itinerary_route_id', 'itinerary_route_location'],
    orderBy: { itinerary_route_id: 'asc' }
  });
  p2Hotels.forEach(h => {
    console.log(`  Route ${h.itinerary_route_id}: "${h.itinerary_route_location}"`);
  });

  console.log('\nPlan 5 Hotels:');
  const p5Hotels = await prisma.dvi_itinerary_plan_hotel_details.findMany({
    where: { itinerary_plan_id: 5 },
    select: {
      itinerary_route_id: true,
      itinerary_route_location: true,
      group_type: true,
    },
    distinct: ['itinerary_route_id', 'itinerary_route_location'],
    orderBy: { itinerary_route_id: 'asc' }
  });
  p5Hotels.forEach(h => {
    console.log(`  Route ${h.itinerary_route_id}: "${h.itinerary_route_location}"`);
  });

  await prisma.$disconnect();
}

compareRouteLocations().catch(console.error);
