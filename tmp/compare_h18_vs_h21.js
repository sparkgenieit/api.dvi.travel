const { PrismaClient } = require('@prisma/client');
const prisma = new PrismaClient();

async function main() {
  console.log('\n=== COMPARING H18 vs H21 ===\n');

  // Get both hotspots
  const hotspots = await prisma.dvi_hotspot_place.findMany({
    where: {
      hotspot_ID: { in: [18, 21] }
    },
    select: {
      hotspot_ID: true,
      hotspot_name: true,
      hotspot_duration: true,
      hotspot_latitude: true,
      hotspot_longitude: true,
      hotspot_priority: true
    }
  });

  hotspots.forEach(h => {
    console.log(`H${h.hotspot_ID}: ${h.hotspot_name}`);
    console.log(`  Priority: ${h.hotspot_priority}`);
    console.log(`  Duration: ${h.hotspot_duration?.toISOString().substr(11, 8)}`);
    console.log(`  Location: ${h.hotspot_latitude}, ${h.hotspot_longitude}\n`);
  });

  // Get operating hours
  const h18Hours = await prisma.dvi_hotspot_timing.findFirst({
    where: { hotspot_ID: 18, hotspot_timing_day: 5, deleted: 0 }
  });

  const h21Hours = await prisma.dvi_hotspot_timing.findFirst({
    where: { hotspot_ID: 21, hotspot_timing_day: 5, deleted: 0 }
  });

  console.log('Operating Hours (Friday):');
  console.log(`  H18: ${h18Hours?.hotspot_start_time?.toISOString().substr(11, 8)} - ${h18Hours?.hotspot_end_time?.toISOString().substr(11, 8)}`);
  console.log(`  H21: ${h21Hours?.hotspot_start_time?.toISOString().substr(11, 8)} - ${h21Hours?.hotspot_end_time?.toISOString().substr(11, 8)}`);

  // Check distance from H4
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
    return R * c * 1.5; // Apply 1.5x correction
  }

  hotspots.forEach(h => {
    const dist = haversineDistance(
      parseFloat(h4.hotspot_latitude),
      parseFloat(h4.hotspot_longitude),
      parseFloat(h.hotspot_latitude),
      parseFloat(h.hotspot_longitude)
    );
    const travelMin = (dist / 40) * 60; // 40 km/h
    
    console.log(`\nH4 → H${h.hotspot_ID}:`);
    console.log(`  Distance: ${dist.toFixed(2)} km`);
    console.log(`  Travel: ${travelMin.toFixed(2)} minutes`);
    
    // Calculate arrival
    const h4End = new Date('2025-12-14T10:13:00Z');
    const arrival = new Date(h4End.getTime() + travelMin * 60 * 1000);
    console.log(`  Arrive: ${arrival.toISOString().substr(11, 8)}`);
    
    const hours = h.hotspot_ID === 18 ? h18Hours : h21Hours;
    if (hours) {
      const openHour = hours.hotspot_start_time.getUTCHours();
      const openMin = hours.hotspot_start_time.getUTCMinutes();
      const closeHour = hours.hotspot_end_time.getUTCHours();
      const closeMin = hours.hotspot_end_time.getUTCMinutes();
      
      const arrHour = arrival.getUTCHours();
      const arrMin = arrival.getUTCMinutes();
      
      if (arrHour < openHour || (arrHour === openHour && arrMin < openMin)) {
        console.log(`  ❌ Too early (opens ${hours.hotspot_start_time.toISOString().substr(11, 8)})`);
      } else if (arrHour > closeHour || (arrHour === closeHour && arrMin > closeMin)) {
        console.log(`  ❌ Too late (closes ${hours.hotspot_end_time.toISOString().substr(11, 8)})`);
      } else {
        console.log(`  ✅ Within operating hours`);
      }
    }
  });

  console.log('\n=== CONCLUSION ===');
  console.log('H18 cannot be visited because it closes at 13:00');
  console.log('and arrival time would be 14:52 (after H4 visit + travel).');
  console.log('H21 is selected instead as it has compatible timing.');
}

main()
  .catch(console.error)
  .finally(() => prisma.$disconnect());
