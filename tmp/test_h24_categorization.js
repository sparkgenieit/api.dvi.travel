const { PrismaClient } = require('@prisma/client');
const prisma = new PrismaClient();

async function main() {
  // Get Route 428 details
  const route = await prisma.dvi_itinerary_route_details.findUnique({
    where: { itinerary_route_ID: 428 },
    select: {
      location_name: true,
      next_visiting_location: true,
      direct_to_next_visiting_place: true
    }
  });

  console.log('\n=== ROUTE 428 DETAILS ===');
  console.log(`From: ${route.location_name}`);
  console.log(`To: ${route.next_visiting_location}`);
  console.log(`Direct: ${route.direct_to_next_visiting_place}\n`);

  // Test containsLocation logic
  const testHotspots = [
    { id: 24, location: 'Pondicherry Airport|Pondicherry' },
    { id: 677, location: 'Pondicherry|Pondicherry Airport' },
    { id: 678, location: 'Pondicherry|Pondicherry Airport' },
    { id: 679, location: 'Pondicherry|Pondicherry Airport' }
  ];

  function containsLocation(hotspotLocation, targetLocation) {
    const locations = hotspotLocation.split('|');
    return locations.some(loc => loc.trim().toLowerCase() === targetLocation.toLowerCase());
  }

  console.log('=== containsLocation() TEST ===\n');
  
  const sourceLoc = route.location_name;
  const destLoc = route.next_visiting_location;

  testHotspots.forEach(h => {
    const matchesSource = containsLocation(h.location, sourceLoc);
    const matchesDest = containsLocation(h.location, destLoc);
    
    console.log(`H${h.id}: "${h.location}"`);
    console.log(`  Source match (${sourceLoc}): ${matchesSource ? '✅' : '❌'}`);
    console.log(`  Dest match (${destLoc}): ${matchesDest ? '✅' : '❌'}`);
    
    if (matchesSource && matchesDest) {
      console.log(`  ⚠️  BOTH source AND destination!`);
    } else if (matchesSource) {
      console.log(`  → SOURCE hotspot`);
    } else if (matchesDest) {
      console.log(`  → DESTINATION hotspot`);
    }
    console.log('');
  });

  // Check if H24 was categorized as SOURCE instead of DESTINATION
  console.log('=== HYPOTHESIS ===');
  console.log('H24 "Pondicherry Airport|Pondicherry" might be matching BOTH:');
  console.log(`  - Source: Chennai? ${containsLocation('Pondicherry Airport|Pondicherry', 'Chennai')}`);
  console.log(`  - Dest: Pondicherry? ${containsLocation('Pondicherry Airport|Pondicherry', 'Pondicherry')}`);
  console.log('\nIf H24 matches source (Chennai), it would be filtered out by the');
  console.log('priority-0 SOURCE filter for direct=0 routes!');
}

main()
  .catch(console.error)
  .finally(() => prisma.$disconnect());
