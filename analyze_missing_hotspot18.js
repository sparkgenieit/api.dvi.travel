const { PrismaClient } = require('@prisma/client');
const prisma = new PrismaClient();

async function analyzeHotspots() {
  // Get hotspot details
  const hotspots = await prisma.$queryRawUnsafe(`
    SELECT 
      hotspot_ID,
      hotspot_name,
      hotspot_location,
      hotspot_priority,
      CAST(hotspot_duration AS CHAR) as hotspot_duration
    FROM dvi_hotspot_place 
    WHERE hotspot_ID IN (18, 21, 677, 678, 679)
    ORDER BY hotspot_ID
  `);

  console.log('\n=== HOTSPOT DETAILS ===\n');
  hotspots.forEach(h => {
    console.log(`Hotspot ${h.hotspot_ID}: ${h.hotspot_name}`);
    console.log(`  Location: ${h.hotspot_location}`);
    console.log(`  Priority: ${h.hotspot_priority}`);
    console.log(`  Duration: ${h.hotspot_duration}`);
    console.log('');
  });

  // Check if hotspot 18 has operating hours restrictions
  const timing18 = await prisma.$queryRawUnsafe(`
    SELECT * FROM dvi_hotspot_timing 
    WHERE hotspot_ID = 18
  `);

  console.log('=== HOTSPOT 18 OPERATING HOURS ===');
  if (timing18.length === 0) {
    console.log('No timing restrictions (open 24/7)');
  } else {
    timing18.forEach(t => {
      console.log(`Day ${t.hotspot_day_of_week}: ${t.hotspot_opening_time} - ${t.hotspot_closing_time}`);
    });
  }

  // Check Route 2 details
  const route = await prisma.$queryRawUnsafe(`
    SELECT 
      itinerary_route_ID,
      itinerary_route_date,
      CAST(route_start_time AS CHAR) as route_start_time,
      CAST(route_end_time AS CHAR) as route_end_time,
      start_from,
      end_destination
    FROM dvi_itinerary_route_details
    WHERE itinerary_route_ID = 401
  `);

  console.log('\n=== ROUTE 401 (Plan 5 Route 2) ===');
  console.log('Date:', route[0].itinerary_route_date);
  console.log('Start time:', route[0].route_start_time);
  console.log('End time:', route[0].route_end_time);
  
  // Get day of week
  const date = new Date(route[0].itinerary_route_date);
  const dayOfWeek = date.getUTCDay(); // 0=Sun, 1=Mon, etc
  console.log('Day of week:', dayOfWeek, ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'][dayOfWeek]);

  await prisma.$disconnect();
}

analyzeHotspots().catch(console.error);
