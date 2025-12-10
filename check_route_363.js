const { PrismaClient } = require('@prisma/client');
const prisma = new PrismaClient();

async function checkRoute363() {
  const route = await prisma.dvi_itinerary_route_details.findFirst({
    where: { 
      itinerary_plan_ID: 5,
      next_visiting_location: 'Pondicherry Airport'
    },
    orderBy: { itinerary_route_ID: 'desc' }
  });
  
  if (!route) {
    console.log('No route found!');
    await prisma.$disconnect();
    return;
  }
  
  console.log('Latest Route 5 -> Pondicherry Airport:');
  console.log('ID:', route.itinerary_route_ID);
  console.log('Location:', route.location_name, '->', route.next_visiting_location);
  console.log('Start Time:', route.route_start_time);
  console.log('End Time:', route.route_end_time);
  console.log('Direct:', route.direct_to_next_visiting_place);
  console.log('Distance:', route.no_of_km);
  
  await prisma.$disconnect();
}

checkRoute363().catch(console.error);
