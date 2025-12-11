const { PrismaClient } = require('@prisma/client');
const prisma = new PrismaClient();

async function main() {
  console.log('\n=== DESTINATION HOTSPOT ORDERING ===\n');

  // Get all Pondicherry hotspots with priority 0
  const pondiDestHotspots = await prisma.dvi_hotspot_place.findMany({
    where: {
      hotspot_priority: 0,
      deleted: 0,
      hotspot_location: {
        contains: 'Pondicherry'
      }
    },
    select: {
      hotspot_ID: true,
      hotspot_name: true,
      hotspot_location: true,
      hotspot_priority: true,
      hotspot_latitude: true,
      hotspot_longitude: true,
      hotspot_duration: true
    },
    orderBy: { hotspot_ID: 'asc' }
  });

  console.log(`Found ${pondiDestHotspots.length} Pondicherry destination hotspots:\n`);

  // Calculate distance from H17 (last regular hotspot before destinations)
  const h17 = await prisma.dvi_hotspot_place.findUnique({
    where: { hotspot_ID: 17 },
    select: { hotspot_latitude: true, hotspot_longitude: true, hotspot_name: true }
  });

  console.log(`Last regular hotspot: H17 ${h17.hotspot_name}\n`);

  function haversineDistance(lat1, lon1, lat2, lon2) {
    const R = 6371;
    const dLat = (lat2 - lat1) * Math.PI / 180;
    const dLon = (lon2 - lon1) * Math.PI / 180;
    const a = Math.sin(dLat / 2) * Math.sin(dLat / 2) +
              Math.cos(lat1 * Math.PI / 180) * Math.cos(lat2 * Math.PI / 180) *
              Math.sin(dLon / 2) * Math.sin(dLon / 2);
    const c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a));
    return R * c * 1.5; // Apply 1.5x correction
  }

  // Calculate distances and sort by PHP logic
  const hotspotsWithDistance = pondiDestHotspots.map(h => {
    const dist = haversineDistance(
      parseFloat(h17.hotspot_latitude),
      parseFloat(h17.hotspot_longitude),
      parseFloat(h.hotspot_latitude),
      parseFloat(h.hotspot_longitude)
    );
    return { ...h, distance: dist };
  });

  // PHP sortHotspots: priority 0 goes last, then sort by distance
  hotspotsWithDistance.sort((a, b) => {
    // All have priority 0, so just sort by distance
    return a.distance - b.distance;
  });

  console.log('Sorted by distance from H17:');
  console.log('-------------------------------------------');
  hotspotsWithDistance.forEach((h, idx) => {
    console.log(`${idx + 1}. H${h.hotspot_ID}: ${h.hotspot_name}`);
    console.log(`   Location: ${h.hotspot_location}`);
    console.log(`   Distance from H17: ${h.distance.toFixed(2)} km`);
    console.log(`   Duration: ${h.hotspot_duration?.toISOString().substr(11, 8)}\n`);
  });

  // Check PHP Route 428 - which ones were actually added?
  const phpSelected = await prisma.dvi_itinerary_route_hotspot_details.findMany({
    where: {
      itinerary_route_ID: 428,
      hotspot_ID: { in: pondiDestHotspots.map(h => h.hotspot_ID) },
      item_type: { in: [1, 4] }
    },
    orderBy: { hotspot_order: 'asc' }
  });

  console.log('=== PHP Route 428 Selected ===');
  phpSelected.forEach(h => {
    const hotspot = hotspotsWithDistance.find(hd => hd.hotspot_ID === h.hotspot_ID);
    console.log(`Order ${h.hotspot_order}: H${h.hotspot_ID} - ${h.hotspot_start_time.toISOString().substr(11, 8)} to ${h.hotspot_end_time.toISOString().substr(11, 8)}`);
    console.log(`   Distance: ${hotspot?.distance.toFixed(2)} km\n`);
  });

  // Check NestJS Route 437
  const nestjsSelected = await prisma.dvi_itinerary_route_hotspot_details.findMany({
    where: {
      itinerary_route_ID: 437,
      hotspot_ID: { in: pondiDestHotspots.map(h => h.hotspot_ID) },
      item_type: { in: [1, 4] }
    },
    orderBy: { hotspot_order: 'asc' }
  });

  console.log('=== NestJS Route 437 Selected ===');
  nestjsSelected.forEach(h => {
    const hotspot = hotspotsWithDistance.find(hd => hd.hotspot_ID === h.hotspot_ID);
    console.log(`Order ${h.hotspot_order}: H${h.hotspot_ID} - ${h.hotspot_start_time.toISOString().substr(11, 8)} to ${h.hotspot_end_time.toISOString().substr(11, 8)}`);
    console.log(`   Distance: ${hotspot?.distance.toFixed(2)} km\n`);
  });

  console.log('=== ANALYSIS ===');
  const phpIds = phpSelected.map(h => h.hotspot_ID);
  const nestjsIds = nestjsSelected.map(h => h.hotspot_ID);
  
  const phpOnly = phpIds.filter(id => !nestjsIds.includes(id));
  const nestjsOnly = nestjsIds.filter(id => !phpIds.includes(id));
  const both = phpIds.filter(id => nestjsIds.includes(id));

  console.log(`Both systems: [${both.join(', ')}]`);
  if (phpOnly.length) console.log(`PHP only: [${phpOnly.join(', ')}]`);
  if (nestjsOnly.length) console.log(`NestJS only: [${nestjsOnly.join(', ')}]`);
}

main()
  .catch(console.error)
  .finally(() => prisma.$disconnect());
