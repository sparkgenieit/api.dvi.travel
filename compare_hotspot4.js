const { PrismaClient } = require('@prisma/client');
const prisma = new PrismaClient();

async function compareHotspot4Details() {
  console.log('\n=== COMPARING HOTSPOT 4 (Kapaleeshwarar Temple) DETAILS ===\n');
  
  // Get Plan 2 Route 2
  const plan2Routes = await prisma.dvi_itinerary_route_details.findMany({
    where: { itinerary_plan_ID: 2, deleted: 0, status: 1 },
    orderBy: { itinerary_route_ID: 'asc' },
  });
  const route2PHP = plan2Routes[1];
  
  // Get Plan 5 Route 2
  const plan5Routes = await prisma.dvi_itinerary_route_details.findMany({
    where: { itinerary_plan_ID: 5, deleted: 0, status: 1 },
    orderBy: { itinerary_route_ID: 'asc' },
  });
  const route2Nest = plan5Routes[1];
  
  console.log('=== PHP Route 2 (Plan 2) ===');
  console.log(`  Route ID: ${route2PHP.itinerary_route_ID}`);
  console.log(`  Start time: ${route2PHP.route_start_time}`);
  
  console.log('\n=== NestJS Route 2 (Plan 5) ===');
  console.log(`  Route ID: ${route2Nest.itinerary_route_ID}`);
  console.log(`  Start time: ${route2Nest.route_start_time}`);
  
  // Get all hotspot 4 entries from PHP timeline
  const phpHotspot4Rows = await prisma.dvi_itinerary_route_hotspot_details.findMany({
    where: {
      itinerary_plan_ID: 2,
      itinerary_route_ID: route2PHP.itinerary_route_ID,
      hotspot_ID: 4
    },
    orderBy: { hotspot_order: 'asc' }
  });
  
  console.log('\n=== PHP HOTSPOT 4 ROWS ===');
  phpHotspot4Rows.forEach(row => {
    console.log(`  Item Type: ${row.item_type} | Order: ${row.hotspot_order}`);
    console.log(`    Start: ${row.hotspot_start_time} | End: ${row.hotspot_end_time}`);
    console.log(`    Travel Time: ${row.hotspot_traveling_time}`);
    console.log(`    Distance: ${row.hotspot_travelling_distance} km`);
    console.log(``);
  });
  
  // Get all hotspot 4 entries from NestJS timeline
  const nestHotspot4Rows = await prisma.dvi_itinerary_route_hotspot_details.findMany({
    where: {
      itinerary_plan_ID: 5,
      itinerary_route_ID: route2Nest.itinerary_route_ID,
      hotspot_ID: 4
    },
    orderBy: { hotspot_order: 'asc' }
  });
  
  console.log('=== NESTJS HOTSPOT 4 ROWS ===');
  if (nestHotspot4Rows.length === 0) {
    console.log('  ❌ No hotspot 4 rows found!');
  } else {
    nestHotspot4Rows.forEach(row => {
      console.log(`  Item Type: ${row.item_type} | Order: ${row.hotspot_order}`);
      console.log(`    Start: ${row.hotspot_start_time} | End: ${row.hotspot_end_time}`);
      console.log(`    Travel Time: ${row.hotspot_traveling_time}`);
      console.log(`    Distance: ${row.hotspot_travelling_distance} km`);
      console.log(``);
    });
  }
  
  // Get hotspot 4 master data
  const hotspot4 = await prisma.dvi_hotspot_place.findUnique({
    where: { hotspot_ID: 4 },
    select: {
      hotspot_ID: true,
      hotspot_name: true,
      hotspot_duration: true,
      hotspot_latitude: true,
      hotspot_longitude: true,
    }
  });
  
  console.log('=== HOTSPOT 4 MASTER DATA ===');
  console.log(`  Name: ${hotspot4.hotspot_name}`);
  console.log(`  Duration: ${hotspot4.hotspot_duration}`);
  console.log(`  Coordinates: (${hotspot4.hotspot_latitude}, ${hotspot4.hotspot_longitude})`);
  
  await prisma.$disconnect();
}

compareHotspot4Details().catch(console.error);
