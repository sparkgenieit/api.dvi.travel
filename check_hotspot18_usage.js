const { PrismaClient } = require('@prisma/client');
const prisma = new PrismaClient();

async function checkHotspot18Usage() {
  // Check if hotspot 18 is used in Plan 5
  const usage = await prisma.$queryRawUnsafe(`
    SELECT 
      itinerary_route_ID,
      hotspot_ID,
      hotspot_order,
      item_type
    FROM dvi_itinerary_route_hotspot_details
    WHERE itinerary_plan_ID = 5
      AND hotspot_ID = 18
    ORDER BY itinerary_route_ID, hotspot_order
  `);

  console.log('\n=== HOTSPOT 18 (Auroville) USAGE IN PLAN 5 ===');
  if (usage.length === 0) {
    console.log('❌ NOT USED AT ALL');
  } else {
    usage.forEach(u => {
      console.log(`Route ${u.itinerary_route_ID}, Order ${u.hotspot_order}, Type ${u.item_type}`);
    });
  }

  // Check PHP Plan 2
  const phpUsage = await prisma.$queryRawUnsafe(`
    SELECT 
      itinerary_route_ID,
      hotspot_ID,
      hotspot_order,
      item_type
    FROM dvi_itinerary_route_hotspot_details
    WHERE itinerary_plan_ID = 2
      AND hotspot_ID = 18
    ORDER BY itinerary_route_ID, hotspot_order
  `);

  console.log('\n=== HOTSPOT 18 (Auroville) USAGE IN PHP PLAN 2 ===');
  phpUsage.forEach(u => {
    console.log(`Route ${u.itinerary_route_ID}, Order ${u.hotspot_order}, Type ${u.item_type}`);
  });

  // Check hotspot 18 timing
  const timing = await prisma.$queryRawUnsafe(`
    SELECT 
      hotspot_day_of_week,
      CAST(hotspot_opening_time AS CHAR) as opening,
      CAST(hotspot_closing_time AS CHAR) as closing
    FROM dvi_hotspot_timing
    WHERE hotspot_ID = 18
    ORDER BY hotspot_day_of_week
  `);

  console.log('\n=== HOTSPOT 18 OPERATING HOURS ===');
  if (timing.length === 0) {
    console.log('No timing restrictions (open 24/7)');
  } else {
    const days = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
    timing.forEach(t => {
      console.log(`${days[t.hotspot_day_of_week]}: ${t.opening} - ${t.closing}`);
    });
  }

  // Check Route 401 date and day of week
  const route = await prisma.$queryRawUnsafe(`
    SELECT 
      itinerary_route_date,
      CAST(route_start_time AS CHAR) as start_time,
      CAST(route_end_time AS CHAR) as end_time
    FROM dvi_itinerary_route_details
    WHERE itinerary_route_ID = 401
  `);

  console.log('\n=== ROUTE 401 (NestJS Route 2) ===');
  const date = new Date(route[0].itinerary_route_date);
  const dayOfWeek = date.getUTCDay();
  const days = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
  console.log(`Date: ${date.toISOString().split('T')[0]}`);
  console.log(`Day: ${days[dayOfWeek]}`);
  console.log(`Start: ${route[0].start_time}`);
  console.log(`End: ${route[0].end_time}`);

  await prisma.$disconnect();
}

checkHotspot18Usage().catch(console.error);
