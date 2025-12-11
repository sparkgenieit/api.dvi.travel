const { PrismaClient } = require('@prisma/client');
const prisma = new PrismaClient();

// Haversine distance calculation
function haversineDistance(lat1, lon1, lat2, lon2) {
  const R = 6371; // Earth's radius in km
  const dLat = (lat2 - lat1) * Math.PI / 180;
  const dLon = (lon2 - lon1) * Math.PI / 180;
  const a = Math.sin(dLat / 2) * Math.sin(dLat / 2) +
            Math.cos(lat1 * Math.PI / 180) * Math.cos(lat2 * Math.PI / 180) *
            Math.sin(dLon / 2) * Math.sin(dLon / 2);
  const c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a));
  return R * c;
}

async function main() {
  console.log('\n=== DISTANCE VERIFICATION FOR HOTSPOT 4 ===\n');

  // Chennai coordinates (from location_id 1)
  const chennai = {
    location_name: 'Chennai',
    location_latitude: '13.0826802',
    location_longitude: '80.2707184'
  };

  // Get Hotspot 4 coordinates
  const hotspot4 = await prisma.dvi_hotspot_place.findUnique({
    where: { hotspot_ID: 4 },
    select: { 
      hotspot_ID: true,
      hotspot_latitude: true, 
      hotspot_longitude: true, 
      hotspot_name: true 
    }
  });

  console.log(`From: ${chennai.location_name} (${chennai.location_latitude}, ${chennai.location_longitude})`);
  console.log(`To: ${hotspot4.hotspot_name} (${hotspot4.hotspot_latitude}, ${hotspot4.hotspot_longitude})\n`);

  // Calculate raw Haversine distance
  const rawDistance = haversineDistance(
    parseFloat(chennai.location_latitude),
    parseFloat(chennai.location_longitude),
    parseFloat(hotspot4.hotspot_latitude),
    parseFloat(hotspot4.hotspot_longitude)
  );

  // Apply 1.5x correction factor (as per PHP code)
  const correctedDistance = rawDistance * 1.5;

  console.log(`Raw Haversine distance: ${rawDistance.toFixed(2)} km`);
  console.log(`Corrected distance (×1.5): ${correctedDistance.toFixed(2)} km\n`);

  // Get NestJS Route 425, hotspot 4 details
  const nestjsH4 = await prisma.dvi_itinerary_route_hotspot_details.findFirst({
    where: {
      itinerary_route_ID: 425,
      hotspot_ID: 4,
      item_type: { in: [1, 4] } // Visit could be 1 or 4
    },
    select: {
      route_hotspot_ID: true,
      item_type: true,
      hotspot_travelling_distance: true,
      hotspot_traveling_time: true,
      hotspot_start_time: true,
      hotspot_end_time: true
    }
  });

  // Get PHP Route 179, hotspot 4 details  
  const phpH4 = await prisma.dvi_itinerary_route_hotspot_details.findFirst({
    where: {
      itinerary_route_ID: 179,
      hotspot_ID: 4,
      item_type: { in: [1, 4] } // Visit could be 1 or 4
    },
    select: {
      route_hotspot_ID: true,
      item_type: true,
      hotspot_travelling_distance: true,
      hotspot_traveling_time: true,
      hotspot_start_time: true,
      hotspot_end_time: true
    }
  });

  // Also get TRAVEL row to hotspot 4
  const phpH4Travel = await prisma.dvi_itinerary_route_hotspot_details.findFirst({
    where: {
      itinerary_route_ID: 179,
      hotspot_ID: 4,
      item_type: 3 // Travel
    },
    select: {
      route_hotspot_ID: true,
      hotspot_travelling_distance: true,
      hotspot_traveling_time: true
    }
  });

  if (nestjsH4) {
    console.log('=== NESTJS ROUTE 425 (Current) ===');
    console.log(`Route Hotspot ID: ${nestjsH4.route_hotspot_ID}`);
    console.log(`Stored distance: ${nestjsH4.hotspot_travelling_distance} km`);
    console.log(`Travel time: ${nestjsH4.hotspot_traveling_time.toISOString().substr(11, 8)}`);
    console.log(`Visit: ${nestjsH4.hotspot_start_time.toISOString().substr(11, 8)} - ${nestjsH4.hotspot_end_time.toISOString().substr(11, 8)}\n`);
  } else {
    console.log('=== NESTJS ROUTE 425 (Current) ===');
    console.log('Not found - route may not exist yet\n');
  }

  console.log('=== PHP ROUTE 179 (Source) ===');
  console.log(`Route Hotspot ID: ${phpH4.route_hotspot_ID} (item_type: ${phpH4.item_type})`);
  console.log(`Stored distance: ${phpH4.hotspot_travelling_distance} km`);
  console.log(`Travel time: ${phpH4.hotspot_traveling_time.toISOString().substr(11, 8)}`);
  console.log(`Visit: ${phpH4.hotspot_start_time.toISOString().substr(11, 8)} - ${phpH4.hotspot_end_time.toISOString().substr(11, 8)}`);
  
  if (phpH4Travel) {
    console.log(`\nTRAVEL row to H4:`);
    console.log(`  Route Hotspot ID: ${phpH4Travel.route_hotspot_ID}`);
    console.log(`  Travel distance: ${phpH4Travel.hotspot_travelling_distance} km`);
    console.log(`  Travel time: ${phpH4Travel.hotspot_traveling_time.toISOString().substr(11, 8)}\n`);
  }

  // Get travel type and speed
  const globalSettings = await prisma.dvi_global_settings.findFirst({
    where: { deleted: 0 },
    select: {
      itinerary_local_speed_limit: true,
      itinerary_outstation_speed_limit: true
    }
  });

  console.log('=== SPEED SETTINGS ===');
  console.log(`Local speed: ${globalSettings.itinerary_local_speed_limit} km/h`);
  console.log(`Outstation speed: ${globalSettings.itinerary_outstation_speed_limit} km/h\n`);

  // Calculate expected travel times
  const localSpeed = parseFloat(globalSettings.itinerary_local_speed_limit);
  const expectedLocalTime = (correctedDistance / localSpeed) * 60; // in minutes

  console.log('=== COMPARISON ===');
  console.log(`Expected travel time at ${localSpeed} km/h: ${expectedLocalTime.toFixed(2)} minutes`);
  
  if (phpH4Travel) {
    const phpTravelMinutes = phpH4Travel.hotspot_traveling_time.getUTCHours() * 60 + 
                             phpH4Travel.hotspot_traveling_time.getUTCMinutes();
    console.log(`PHP TRAVEL row: ${phpTravelMinutes} minutes`);
  }
  
  if (nestjsH4) {
    const nestjsMinutes = nestjsH4.hotspot_traveling_time.getUTCHours() * 60 + 
                          nestjsH4.hotspot_traveling_time.getUTCMinutes();
    console.log(`NestJS stored: ${nestjsMinutes} minutes`);
    
    if (phpH4Travel) {
      const phpTravelMinutes = phpH4Travel.hotspot_traveling_time.getUTCHours() * 60 + 
                               phpH4Travel.hotspot_traveling_time.getUTCMinutes();
      console.log(`Difference: ${Math.abs(phpTravelMinutes - nestjsMinutes)} minutes\n`);
    }

    // Check if distances match
    const phpDist = phpH4Travel ? phpH4Travel.hotspot_travelling_distance : phpH4.hotspot_travelling_distance;
    if (nestjsH4.hotspot_travelling_distance === phpDist) {
      console.log('✅ Both store SAME distance:', nestjsH4.hotspot_travelling_distance, 'km');
    } else {
      console.log('❌ DIFFERENT distances stored!');
      console.log(`   NestJS: ${nestjsH4.hotspot_travelling_distance} km`);
      console.log(`   PHP: ${phpDist} km`);
    }
  } else if (phpH4Travel) {
    const phpTravelMinutes = phpH4Travel.hotspot_traveling_time.getUTCHours() * 60 + 
                             phpH4Travel.hotspot_traveling_time.getUTCMinutes();
    console.log(`Difference from expected: ${Math.abs(phpTravelMinutes - expectedLocalTime).toFixed(2)} minutes\n`);
    console.log(`⚠️  NestJS Route 425 not found for comparison`);
    console.log(`PHP distance stored: ${phpH4Travel.hotspot_travelling_distance} km`);
  }
}

main()
  .catch(console.error)
  .finally(() => prisma.$disconnect());
