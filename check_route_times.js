const { PrismaClient } = require('@prisma/client');
const prisma = new PrismaClient();

async function checkRouteTimes() {
  try {
    console.log('=== PHP PLAN 2 ROUTE 178 ===\n');
    
    const phpRoute = await prisma.dvi_itinerary_route_details.findUnique({
      where: { itinerary_route_ID: 178 },
      select: {
        itinerary_route_ID: true,
        itinerary_plan_ID: true,
        location_name: true,
        next_visiting_location: true,
        route_start_time: true,
        route_end_time: true,
        direct_to_next_visiting_place: true
      }
    });
    
    console.log('Route 178:');
    console.log('  Plan ID:', phpRoute.itinerary_plan_ID);
    console.log('  From:', phpRoute.location_name);
    console.log('  To:', phpRoute.next_visiting_location);
    console.log('  Start Time:', phpRoute.route_start_time);
    console.log('  End Time:', phpRoute.route_end_time);
    console.log('  Direct:', phpRoute.direct_to_next_visiting_place);
    
    console.log('\n=== NESTJS PLAN 5 ROUTE 214 ===\n');
    
    const nestRoute = await prisma.dvi_itinerary_route_details.findUnique({
      where: { itinerary_route_ID: 214 },
      select: {
        itinerary_route_ID: true,
        itinerary_plan_ID: true,
        location_name: true,
        next_visiting_location: true,
        route_start_time: true,
        route_end_time: true,
        direct_to_next_visiting_place: true
      }
    });
    
    if (nestRoute) {
      console.log('Route 214:');
      console.log('  Plan ID:', nestRoute.itinerary_plan_ID);
      console.log('  From:', nestRoute.location_name);
      console.log('  To:', nestRoute.next_visiting_location);
      console.log('  Start Time:', nestRoute.route_start_time);
      console.log('  End Time:', nestRoute.route_end_time);
      console.log('  Direct:', nestRoute.direct_to_next_visiting_place);
    }
    
    console.log('\n=== COMPARING ===\n');
    console.log('PHP Route 178 starts at:', phpRoute.route_start_time);
    console.log('NestJS Route 214 starts at:', nestRoute?.route_start_time);
    
    await prisma.$disconnect();
  } catch (error) {
    console.error('Error:', error);
    await prisma.$disconnect();
  }
}

checkRouteTimes();
