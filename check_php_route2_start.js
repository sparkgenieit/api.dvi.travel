const { PrismaClient } = require('@prisma/client');
const prisma = new PrismaClient();

async function checkPHPRoute2StartTime() {
  const route = await prisma.dvi_itinerary_route_master.findFirst({
    where: { itinerary_plan_ID: 2, itinerary_route_ID: 179 }
  });

  if (!route) {
    console.log('Route 179 not found');
    return;
  }

  const start = `${String(route.itinerary_route_start_time.getUTCHours()).padStart(2, '0')}:${String(route.itinerary_route_start_time.getUTCMinutes()).padStart(2, '0')}`;
  const end = `${String(route.itinerary_route_end_time.getUTCHours()).padStart(2, '0')}:${String(route.itinerary_route_end_time.getUTCMinutes()).padStart(2, '0')}`;
  
  console.log(`\n=== PHP PLAN 2 ROUTE 179 ===`);
  console.log(`Source: ${route.itinerary_route_source_location}`);
  console.log(`Destination: ${route.itinerary_route_destination_location}`);
  console.log(`Start time: ${start}`);
  console.log(`End time: ${end}`);
  console.log(`Date: ${route.itinerary_route_date.toISOString().split('T')[0]}`);

  await prisma.$disconnect();
}

checkPHPRoute2StartTime();
