const { PrismaClient } = require('@prisma/client');
const prisma = new PrismaClient();

async function main() {
  const route406 = await prisma.dvi_itinerary_route_details.findUnique({
    where: {
      itinerary_route_ID: 406,
    },
  });

  console.log('\n=== Route 406 Details ===');
  console.log('Date:', route406.itinerary_route_date);
  console.log('Start Time:', route406.route_start_time);
  console.log('End Time:', route406.route_end_time);
  console.log('Location:', route406.location_name);
  console.log('Direct:', route406.direct_to_next_visiting_place);
  console.log('Next Location:', route406.next_visiting_location);

  await prisma.$disconnect();
}

main();
