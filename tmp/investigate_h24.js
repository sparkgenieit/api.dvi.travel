const { PrismaClient } = require('@prisma/client');
const prisma = new PrismaClient();

async function main() {
  console.log('\n=== INVESTIGATING HOTSPOT 24 ===\n');

  // Get H24 details
  const h24 = await prisma.dvi_hotspot_place.findUnique({
    where: { hotspot_ID: 24 },
    select: {
      hotspot_ID: true,
      hotspot_name: true,
      hotspot_type: true,
      hotspot_priority: true,
      hotspot_duration: true,
      hotspot_latitude: true,
      hotspot_longitude: true,
      hotspot_location: true
    }
  });

  console.log('H24 Details:');
  console.log(`  Name: ${h24.hotspot_name}`);
  console.log(`  Type: ${h24.hotspot_type}`);
  console.log(`  Priority: ${h24.hotspot_priority}`);
  console.log(`  Duration: ${h24.hotspot_duration?.toISOString().substr(11, 8)}`);
  console.log(`  Location: ${h24.hotspot_location}`);
  console.log(`  Coords: ${h24.hotspot_latitude}, ${h24.hotspot_longitude}`);

  // Get operating hours for Friday (day 5)
  const h24Hours = await prisma.dvi_hotspot_timing.findFirst({
    where: { hotspot_ID: 24, hotspot_timing_day: 5, deleted: 0 }
  });

  if (h24Hours) {
    console.log(`  Hours (Fri): ${h24Hours.hotspot_start_time?.toISOString().substr(11, 8)} - ${h24Hours.hotspot_end_time?.toISOString().substr(11, 8)}`);
  }

  // Check what's in Route 437 (NestJS) for H24
  const nestjsH24 = await prisma.dvi_itinerary_route_hotspot_details.findFirst({
    where: {
      itinerary_route_ID: 437,
      hotspot_ID: 24,
      item_type: { in: [1, 4] }
    }
  });

  if (nestjsH24) {
    console.log(`\nH24 in NestJS Route 437:`);
    console.log(`  Order: ${nestjsH24.hotspot_order}`);
    console.log(`  Visit: ${nestjsH24.hotspot_start_time.toISOString().substr(11, 8)} - ${nestjsH24.hotspot_end_time.toISOString().substr(11, 8)}`);
  }

  // Compare with destination hotspots
  console.log('\n=== DESTINATION HOTSPOTS (Priority 0) ===\n');

  const destHotspots = await prisma.dvi_hotspot_place.findMany({
    where: {
      hotspot_ID: { in: [24, 677, 678, 679] }
    },
    select: {
      hotspot_ID: true,
      hotspot_name: true,
      hotspot_priority: true,
      hotspot_location: true
    },
    orderBy: { hotspot_ID: 'asc' }
  });

  destHotspots.forEach(h => {
    console.log(`H${h.hotspot_ID}: ${h.hotspot_name}`);
    console.log(`  Priority: ${h.hotspot_priority}`);
    console.log(`  Location: ${h.hotspot_location}\n`);
  });

  // Check which ones match "Pondicherry"
  const pondiHotspots = destHotspots.filter(h => 
    h.hotspot_location?.toLowerCase().includes('pondicherry') ||
    h.hotspot_location?.toLowerCase().includes('puducherry')
  );

  console.log('Pondicherry hotspots:');
  pondiHotspots.forEach(h => {
    console.log(`  H${h.hotspot_ID}: ${h.hotspot_name} (Priority ${h.hotspot_priority})`);
  });

  // Check Route 428 (PHP) - what destination hotspots does it have?
  const phpDest = await prisma.dvi_itinerary_route_hotspot_details.findMany({
    where: {
      itinerary_route_ID: 428,
      hotspot_ID: { in: [24, 677, 678, 679] },
      item_type: { in: [1, 4] }
    },
    orderBy: { hotspot_order: 'asc' }
  });

  console.log('\n=== PHP Route 428 Destination Hotspots ===');
  phpDest.forEach(h => {
    console.log(`  Order ${h.hotspot_order}: H${h.hotspot_ID}`);
  });

  // Check Route 437 (NestJS)
  const nestjsDest = await prisma.dvi_itinerary_route_hotspot_details.findMany({
    where: {
      itinerary_route_ID: 437,
      hotspot_ID: { in: [24, 677, 678, 679] },
      item_type: { in: [1, 4] }
    },
    orderBy: { hotspot_order: 'asc' }
  });

  console.log('\n=== NestJS Route 437 Destination Hotspots ===');
  nestjsDest.forEach(h => {
    console.log(`  Order ${h.hotspot_order}: H${h.hotspot_ID}`);
  });

  console.log('\n=== ANALYSIS ===');
  console.log(`PHP selects: [${phpDest.map(h => h.hotspot_ID).join(', ')}]`);
  console.log(`NestJS selects: [${nestjsDest.map(h => h.hotspot_ID).join(', ')}]`);
  
  if (h24.hotspot_priority === 0) {
    console.log('\n⚠️  H24 is priority 0 (destination hotspot)');
    console.log('NestJS is including an extra destination hotspot that PHP skips.');
  }
}

main()
  .catch(console.error)
  .finally(() => prisma.$disconnect());
