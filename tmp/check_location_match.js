const { PrismaClient } = require('@prisma/client');
const prisma = new PrismaClient();

async function main() {
  // Get Route 425 details
  const route = await prisma.dvi_itinerary_route_details.findUnique({
    where: { itinerary_route_ID: 425 },
    select: {
      location_name: true,
    }
  });

  console.log('\n=== Route 425 Starting Location ===');
  console.log(route.location_name);

  // Get hotspot 4 location
  const h4 = await prisma.dvi_hotspot_place.findUnique({
    where: { hotspot_ID: 4 },
    select: {
      hotspot_location: true,
    }
  });

  console.log('\n=== Hotspot 4 Location ===');
  console.log(h4.hotspot_location);

  // Check if they match
  const routeLocations = route.location_name.split('|').map(s => s.trim());
  const hotspotLocations = h4.hotspot_location.split('|').map(s => s.trim());

  console.log('\n=== Checking for Matches ===');
  console.log('Route locations:', routeLocations);
  console.log('Hotspot locations:', hotspotLocations);

  let foundMatch = false;
  for (const rLoc of routeLocations) {
    for (const hLoc of hotspotLocations) {
      if (rLoc === hLoc) {
        console.log(`\n✅ MATCH FOUND: "${rLoc}"`);
        foundMatch = true;
      }
    }
  }

  if (!foundMatch) {
    console.log('\n❌ NO MATCH - Will use OUTSTATION speed (60 km/h)');
  } else {
    console.log('\n✅ MATCH - Will use LOCAL speed (40 km/h)');
  }
}

main()
  .catch(console.error)
  .finally(() => prisma.$disconnect());
