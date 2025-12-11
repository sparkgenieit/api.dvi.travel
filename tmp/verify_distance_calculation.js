const { PrismaClient } = require('@prisma/client');
const prisma = new PrismaClient();

// Haversine formula
function haversineDistance(lat1, lon1, lat2, lon2) {
  const earthRadius = 6371; // km
  
  const lat1Rad = (lat1 * Math.PI) / 180;
  const lon1Rad = (lon1 * Math.PI) / 180;
  const lat2Rad = (lat2 * Math.PI) / 180;
  const lon2Rad = (lon2 * Math.PI) / 180;
  
  const latDiff = lat2Rad - lat1Rad;
  const lonDiff = lon2Rad - lon1Rad;
  
  const a = Math.pow(Math.sin(latDiff / 2), 2) +
    Math.cos(lat1Rad) * Math.cos(lat2Rad) *
    Math.pow(Math.sin(lonDiff / 2), 2);
  
  const distance = 2 * earthRadius * Math.asin(Math.sqrt(a));
  
  return distance;
}

async function main() {
  // Get Route 425 starting location coords (Chennai)
  const route = await prisma.dvi_itinerary_route_details.findUnique({
    where: { itinerary_route_ID: 425 },
    select: {
      route_from_location: true,
    }
  });

  // Get location coords for Chennai
  const location = await prisma.dvi_location_place.findFirst({
    where: {
      location_name: route.route_from_location,
      deleted: 0,
      status: 1,
    },
    select: {
      location_latitude: true,
      location_longitude: true,
    }
  });

  // Get hotspot 4 coords
  const hotspot = await prisma.dvi_hotspot_place.findUnique({
    where: { hotspot_ID: 4 },
    select: {
      hotspot_name: true,
      hotspot_latitude: true,
      hotspot_longitude: true,
    }
  });

  console.log('\n=== Coordinates ===');
  console.log(`Start (${route.route_from_location}): ${location.location_latitude}, ${location.location_longitude}`);
  console.log(`End (${hotspot.hotspot_name}): ${hotspot.hotspot_latitude}, ${hotspot.hotspot_longitude}`);

  const lat1 = Number(location.location_latitude);
  const lon1 = Number(location.location_longitude);
  const lat2 = Number(hotspot.hotspot_latitude);
  const lon2 = Number(hotspot.hotspot_longitude);

  const rawDistance = haversineDistance(lat1, lon1, lat2, lon2);
  const correctedDistance = rawDistance * 1.5;

  console.log('\n=== Distance Calculation ===');
  console.log(`Raw distance (Haversine): ${rawDistance.toFixed(4)} km`);
  console.log(`Correction factor: 1.5`);
  console.log(`Corrected distance: ${correctedDistance.toFixed(2)} km`);

  // Check stored distance
  const stored = await prisma.dvi_itinerary_route_hotspot_details.findFirst({
    where: {
      itinerary_route_ID: 425,
      hotspot_ID: 4,
      item_type: 3,
      deleted: 0,
      status: 1,
    },
    select: {
      hotspot_travelling_distance: true,
    }
  });

  console.log(`\nStored in database: ${stored.hotspot_travelling_distance} km`);
  console.log(parseFloat(stored.hotspot_travelling_distance) === parseFloat(correctedDistance.toFixed(2)) ? '✅ MATCH!' : '❌ DIFFERENT!');

  // Also check PHP
  const phpStored = await prisma.dvi_itinerary_route_hotspot_details.findFirst({
    where: {
      itinerary_route_ID: 179,
      hotspot_ID: 4,
      item_type: 3,
      deleted: 0,
      status: 1,
    },
    select: {
      hotspot_travelling_distance: true,
    }
  });

  console.log(`\n=== PHP Route 179 ===`);
  console.log(`Stored in database: ${phpStored.hotspot_travelling_distance} km`);
  console.log(parseFloat(phpStored.hotspot_travelling_distance) === parseFloat(correctedDistance.toFixed(2)) ? '✅ SAME as NestJS' : '❌ DIFFERENT from NestJS');
}

main()
  .catch(console.error)
  .finally(() => prisma.$disconnect());
