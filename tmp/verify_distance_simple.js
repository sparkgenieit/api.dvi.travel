const { PrismaClient } = require('@prisma/client');
const prisma = new PrismaClient();

// Haversine formula
function haversine(lat1, lon1, lat2, lon2) {
  const R = 6371; // Earth radius in km
  const lat1Rad = (lat1 * Math.PI) / 180;
  const lon1Rad = (lon1 * Math.PI) / 180;
  const lat2Rad = (lat2 * Math.PI) / 180;
  const lon2Rad = (lon2 * Math.PI) / 180;
  
  const latDiff = lat2Rad - lat1Rad;
  const lonDiff = lon2Rad - lon1Rad;
  
  const a = Math.pow(Math.sin(latDiff / 2), 2) +
    Math.cos(lat1Rad) * Math.cos(lat2Rad) *
    Math.pow(Math.sin(lonDiff / 2), 2);
  
  return 2 * R * Math.asin(Math.sqrt(a));
}

async function main() {
  // Get Route 425 location
  const route = await prisma.dvi_itinerary_route_details.findUnique({
    where: { itinerary_route_ID: 425 },
    select: { location_name: true }
  });

  console.log('\n=== Verifying Distance Calculation ===');
  console.log('Route 425 starts from:', route.location_name);

  // For Chennai, we need coords - let me get from first break item
  const firstItem = await prisma.dvi_itinerary_route_hotspot_details.findFirst({
    where: {
      itinerary_route_ID: 425,
      deleted: 0,
      status: 1,
    },
    orderBy: { hotspot_order: 'asc' }
  });

  // Get hotspot 4 coords
  const h4 = await prisma.dvi_hotspot_place.findUnique({
    where: { hotspot_ID: 4 },
    select: {
      hotspot_name: true,
      hotspot_latitude: true,
      hotspot_longitude: true,
    }
  });

  console.log('\nHotspot 4:', h4.hotspot_name);
  console.log('Coords:', h4.hotspot_latitude, ',', h4.hotspot_longitude);

  // For starting point, use Chennai approximate center
  const chennaiLat = 13.0827;
  const chennaiLon = 80.2707;
  
  console.log('\nChennai approximate center:', chennaiLat, ',', chennaiLon);

  // Calculate raw distance
  const rawDist = haversine(chennaiLat, chennaiLon, Number(h4.hotspot_latitude), Number(h4.hotspot_longitude));
  const correctedDist = rawDist * 1.5;

  console.log('\n=== Distance Calculation ===');
  console.log('Raw Haversine distance:', rawDist.toFixed(2), 'km');
  console.log('With 1.5x correction:', correctedDist.toFixed(2), 'km');
  console.log('Stored in database:', '8.42 km');
  
  console.log('\n=== Travel Time at Different Speeds ===');
  console.log(`At 40 km/h: ${(correctedDist / 40 * 60).toFixed(1)} minutes`);
  console.log(`At 15 km/h: ${(correctedDist / 15 * 60).toFixed(1)} minutes`);
  
  console.log('\n=== What speed gives 34 minutes? ===');
  const speedFor34 = correctedDist / (34 / 60);
  console.log(`Required: ${speedFor34.toFixed(2)} km/h`);
}

main()
  .catch(console.error)
  .finally(() => prisma.$disconnect());
