const { PrismaClient } = require('@prisma/client');
const prisma = new PrismaClient();

async function main() {
  // Get hotspot 4 details
  const h4 = await prisma.dvi_hotspot_place.findUnique({
    where: { hotspot_ID: 4 },
    select: {
      hotspot_ID: true,
      hotspot_name: true,
      hotspot_location: true,
      hotspot_latitude: true,
      hotspot_longitude: true,
    }
  });

  console.log('\n=== Hotspot 4 ===');
  console.log(JSON.stringify(h4, null, 2));

  // Get travel details from both routes
  console.log('\n=== NestJS Route 425 - Travel to Hotspot 4 ===');
  const nestTravel = await prisma.dvi_itinerary_route_hotspot_details.findFirst({
    where: {
      itinerary_route_ID: 425,
      hotspot_ID: 4,
      item_type: 3, // Travel
      deleted: 0,
      status: 1,
    },
    select: {
      hotspot_traveling_time: true,
      hotspot_travelling_distance: true,
      hotspot_start_time: true,
      hotspot_end_time: true,
    }
  });
  
  if (nestTravel) {
    const start = nestTravel.hotspot_start_time;
    const end = nestTravel.hotspot_end_time;
    const startUTC = `${String(start.getUTCHours()).padStart(2, '0')}:${String(start.getUTCMinutes()).padStart(2, '0')}`;
    const endUTC = `${String(end.getUTCHours()).padStart(2, '0')}:${String(end.getUTCMinutes()).padStart(2, '0')}`;
    
    const travelTime = nestTravel.hotspot_traveling_time;
    const travelHours = travelTime.getUTCHours();
    const travelMinutes = travelTime.getUTCMinutes();
    
    console.log(`Travel time stored: ${travelHours}h ${travelMinutes}m`);
    console.log(`Distance: ${nestTravel.hotspot_travelling_distance}`);
    console.log(`Start: ${startUTC} UTC, End: ${endUTC} UTC`);
    
    const actualMinutes = (end.getUTCHours() - start.getUTCHours()) * 60 + (end.getUTCMinutes() - start.getUTCMinutes());
    console.log(`Actual travel time: ${actualMinutes} minutes`);
  } else {
    console.log('NOT FOUND');
  }

  console.log('\n=== PHP Route 179 - Travel to Hotspot 4 ===');
  const phpTravel = await prisma.dvi_itinerary_route_hotspot_details.findFirst({
    where: {
      itinerary_route_ID: 179,
      hotspot_ID: 4,
      item_type: 3, // Travel
      deleted: 0,
      status: 1,
    },
    select: {
      hotspot_traveling_time: true,
      hotspot_travelling_distance: true,
      hotspot_start_time: true,
      hotspot_end_time: true,
    }
  });
  
  if (phpTravel) {
    const start = phpTravel.hotspot_start_time;
    const end = phpTravel.hotspot_end_time;
    const startUTC = `${String(start.getUTCHours()).padStart(2, '0')}:${String(start.getUTCMinutes()).padStart(2, '0')}`;
    const endUTC = `${String(end.getUTCHours()).padStart(2, '0')}:${String(end.getUTCMinutes()).padStart(2, '0')}`;
    
    const travelTime = phpTravel.hotspot_traveling_time;
    const travelHours = travelTime.getUTCHours();
    const travelMinutes = travelTime.getUTCMinutes();
    
    console.log(`Travel time stored: ${travelHours}h ${travelMinutes}m`);
    console.log(`Distance: ${phpTravel.hotspot_travelling_distance}`);
    console.log(`Start: ${startUTC} UTC, End: ${endUTC} UTC`);
    
    const actualMinutes = (end.getUTCHours() - start.getUTCHours()) * 60 + (end.getUTCMinutes() - start.getUTCMinutes());
    console.log(`Actual travel time: ${actualMinutes} minutes`);
  } else {
    console.log('NOT FOUND');
  }

  // Check the route starting location
  console.log('\n=== Route Starting Locations ===');
  const route425 = await prisma.dvi_itinerary_route_details.findUnique({
    where: { itinerary_route_ID: 425 },
    select: {
      itinerary_route_starting_point: true,
      itinerary_route_ending_point: true,
    }
  });
  console.log('NestJS Route 425:', route425);

  const route179 = await prisma.dvi_itinerary_route_details.findUnique({
    where: { itinerary_route_ID: 179 },
    select: {
      itinerary_route_starting_point: true,
      itinerary_route_ending_point: true,
    }
  });
  console.log('PHP Route 179:', route179);
}

main()
  .catch(console.error)
  .finally(() => prisma.$disconnect());
