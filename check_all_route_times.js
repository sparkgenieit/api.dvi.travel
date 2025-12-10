const { PrismaClient } = require('@prisma/client');
const prisma = new PrismaClient();

async function checkRouteTimes() {
  console.log('=== CHECKING ROUTE START TIMES ===\n');
  
  const plan2Routes = await prisma.dvi_itinerary_route_details.findMany({
    where: { itinerary_plan_ID: 2 },
    orderBy: { itinerary_route_ID: 'asc' }
  });
  
  const plan5Routes = await prisma.dvi_itinerary_route_details.findMany({
    where: { itinerary_plan_ID: 5 },
    orderBy: { itinerary_route_ID: 'asc' }
  });
  
  console.log('Plan 2 Routes:');
  plan2Routes.forEach(r => {
    console.log(`  Route ${r.itinerary_route_ID}: ${r.location_name} → ${r.next_visiting_location}`);
    console.log(`    Start: ${r.route_start_time}, End: ${r.route_end_time}`);
    console.log(`    Date: ${r.itinerary_route_date}`);
  });
  
  console.log('\nPlan 5 Routes:');
  plan5Routes.forEach(r => {
    console.log(`  Route ${r.itinerary_route_ID}: ${r.location_name} → ${r.next_visiting_location}`);
    console.log(`    Start: ${r.route_start_time}, End: ${r.route_end_time}`);
    console.log(`    Date: ${r.itinerary_route_date}`);
  });
  
  await prisma.$disconnect();
}

checkRouteTimes();
