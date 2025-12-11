const { PrismaClient } = require('@prisma/client');
const prisma = new PrismaClient();

async function main() {
  console.log('\n=== CHECKING H18 TIMING IN FRESH PHP PLAN 2 ===\n');

  // Get Route 428 (2nd route)
  const hotspots = await prisma.dvi_itinerary_route_hotspot_details.findMany({
    where: {
      itinerary_route_ID: 428,
      deleted: 0
    },
    orderBy: { hotspot_order: 'asc' }
  });

  // Find H4 visit end time
  const h4Visit = hotspots.find(h => h.hotspot_ID === 4 && [1, 4].includes(h.item_type));
  
  if (h4Visit) {
    console.log('H4 visit ends at:', h4Visit.hotspot_end_time.toISOString().substr(11, 8));
  }

  // Get H18 details
  const h18 = await prisma.dvi_hotspot_place.findUnique({
    where: { hotspot_ID: 18 },
    select: {
      hotspot_ID: true,
      hotspot_name: true,
      hotspot_duration: true,
      hotspot_latitude: true,
      hotspot_longitude: true
    }
  });

  console.log('\nH18 Details:');
  console.log(`  Name: ${h18.hotspot_name}`);
  console.log(`  Duration: ${h18.hotspot_duration?.toISOString().substr(11, 8)}`);
  console.log(`  Location: ${h18.hotspot_latitude}, ${h18.hotspot_longitude}`);

  // Get H18 operating hours for day 2 (Friday = 5)
  const h18Hours = await prisma.dvi_hotspot_timing.findFirst({
    where: {
      hotspot_ID: 18,
      hotspot_timing_day: 5, // Friday
      deleted: 0
    },
    select: {
      hotspot_start_time: true,
      hotspot_end_time: true,
      hotspot_timing_day: true
    }
  });

  if (h18Hours) {
    console.log('\nH18 Operating Hours (Friday):');
    console.log(`  Opens: ${h18Hours.hotspot_start_time?.toISOString().substr(11, 8)}`);
    console.log(`  Closes: ${h18Hours.hotspot_end_time?.toISOString().substr(11, 8)}`);
  }

  // Calculate distance from H4 to H18
  const h4 = await prisma.dvi_hotspot_place.findUnique({
    where: { hotspot_ID: 4 },
    select: { hotspot_latitude: true, hotspot_longitude: true }
  });

  function haversineDistance(lat1, lon1, lat2, lon2) {
    const R = 6371;
    const dLat = (lat2 - lat1) * Math.PI / 180;
    const dLon = (lon2 - lon1) * Math.PI / 180;
    const a = Math.sin(dLat / 2) * Math.sin(dLat / 2) +
              Math.cos(lat1 * Math.PI / 180) * Math.cos(lat2 * Math.PI / 180) *
              Math.sin(dLon / 2) * Math.sin(dLon / 2);
    const c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a));
    return R * c;
  }

  const rawDist = haversineDistance(
    parseFloat(h4.hotspot_latitude),
    parseFloat(h4.hotspot_longitude),
    parseFloat(h18.hotspot_latitude),
    parseFloat(h18.hotspot_longitude)
  );
  const correctedDist = rawDist * 1.5;

  console.log('\nDistance H4 → H18:');
  console.log(`  Raw: ${rawDist.toFixed(2)} km`);
  console.log(`  Corrected (×1.5): ${correctedDist.toFixed(2)} km`);

  const travelMinutes = (correctedDist / 40) * 60; // 40 km/h local speed
  console.log(`  Travel time at 40 km/h: ${travelMinutes.toFixed(2)} minutes`);

  if (h4Visit) {
    const h4EndTime = h4Visit.hotspot_end_time;
    const arrivalTime = new Date(h4EndTime.getTime() + travelMinutes * 60 * 1000);
    
    console.log('\nCalculated arrival at H18:');
    console.log(`  From H4 end: ${h4EndTime.toISOString().substr(11, 8)}`);
    console.log(`  Travel: ${travelMinutes.toFixed(2)} minutes`);
    console.log(`  Arrive: ${arrivalTime.toISOString().substr(11, 8)}`);
    
    if (h18Hours) {
      const openTime = h18Hours.hotspot_timing_start.toISOString().substr(11, 8);
      console.log(`  H18 opens: ${openTime}`);
      
      if (arrivalTime.getUTCHours() < 13 || 
          (arrivalTime.getUTCHours() === 13 && arrivalTime.getUTCMinutes() < 30)) {
        console.log('\n  ❌ Arrives BEFORE opening time (13:30:00)');
        console.log('  ⚠️  This is why H18 is being skipped!');
      } else {
        console.log('\n  ✅ Arrives AFTER opening time');
      }
    }
  }

  // Check what comes after H4 in the route
  const afterH4 = hotspots.find(h => h.hotspot_order === 3 && [1, 4].includes(h.item_type));
  if (afterH4) {
    console.log(`\n  Next hotspot after H4: H${afterH4.hotspot_ID}`);
    console.log(`  Start time: ${afterH4.hotspot_start_time.toISOString().substr(11, 8)}`);
  }
}

main()
  .catch(console.error)
  .finally(() => prisma.$disconnect());
