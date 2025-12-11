const { PrismaClient } = require('@prisma/client');
const prisma = new PrismaClient();

async function debugRoute3Timing() {
  console.log('\n=== DEBUGGING ROUTE 3 TIMING AND HOTSPOT SELECTION ===\n');

  // Get Route 3 details for both plans
  const plan2Routes = await prisma.dvi_itinerary_route_details.findMany({
    where: { itinerary_plan_ID: 2, deleted: 0 },
    orderBy: { itinerary_route_date: 'asc' }
  });

  const plan5Routes = await prisma.dvi_itinerary_route_details.findMany({
    where: { itinerary_plan_ID: 5, deleted: 0 },
    orderBy: { itinerary_route_date: 'asc' }
  });

  const plan2Route3 = plan2Routes[2]; // Third route (index 2)
  const plan5Route3 = plan5Routes[2];

  console.log('Plan 2 Route 3 ID:', plan2Route3.itinerary_route_ID);
  console.log('Plan 5 Route 3 ID:', plan5Route3.itinerary_route_ID);
  console.log();

  // Get hotspot details with timing
  const plan2Hotspots = await prisma.dvi_itinerary_route_hotspot_details.findMany({
    where: {
      itinerary_route_ID: plan2Route3.itinerary_route_ID,
      item_type: 4 // Visit rows only
    },
    orderBy: { hotspot_order: 'asc' }
  });

  const plan5Hotspots = await prisma.dvi_itinerary_route_hotspot_details.findMany({
    where: {
      itinerary_route_ID: plan5Route3.itinerary_route_ID,
      item_type: 4 // Visit rows only
    },
    orderBy: { hotspot_order: 'asc' }
  });

  console.log('=== PLAN 2 ROUTE 3 VISIT TIMING ===');
  for (const h of plan2Hotspots) {
    const start = h.hotspot_start_time;
    const end = h.hotspot_end_time;
    const startStr = start instanceof Date ? start.toTimeString().substring(0,5) : (start ? start.toString().substring(16,21) : 'N/A');
    const endStr = end instanceof Date ? end.toTimeString().substring(0,5) : (end ? end.toString().substring(16,21) : 'N/A');
    console.log(`Hotspot ${h.hotspot_ID}: Order ${h.hotspot_order}, Start: ${startStr}, End: ${endStr}`);
  }

  console.log('\n=== PLAN 5 ROUTE 3 VISIT TIMING ===');
  for (const h of plan5Hotspots) {
    const start = h.hotspot_start_time;
    const end = h.hotspot_end_time;
    const startStr = start instanceof Date ? start.toTimeString().substring(0,5) : (start ? start.toString().substring(16,21) : 'N/A');
    const endStr = end instanceof Date ? end.toTimeString().substring(0,5) : (end ? end.toString().substring(16,21) : 'N/A');
    console.log(`Hotspot ${h.hotspot_ID}: Order ${h.hotspot_order}, Start: ${startStr}, End: ${endStr}`);
  }

  // Get hotspot details
  const hotspotIds = [16, 18, 20, 23, 24, 25, 676, 669];
  const hotspotPlace = await prisma.dvi_hotspot_place.findMany({
    where: { hotspot_ID: { in: hotspotIds } },
    orderBy: { hotspot_ID: 'asc' }
  });

  const hotspotTiming = await prisma.dvi_hotspot_timing.findMany({
    where: { 
      hotspot_ID: { in: hotspotIds },
      deleted: 0,
      hotspot_open_all_time: 0,  // Not open 24/7
      hotspot_closed: 0  // Not permanently closed
    }
  });

  console.log('\n=== HOTSPOT DETAILS ===');
  for (const h of hotspotPlace) {
    console.log(`\nID ${h.hotspot_ID}: ${h.hotspot_name}`);
    console.log(`  Location: ${h.hotspot_location}`);
    console.log(`  Type: ${h.hotspot_type}`);
    
    // Find timing for this hotspot
    const timing = hotspotTiming.filter(t => t.hotspot_ID === h.hotspot_ID);
    if (timing.length > 0) {
      console.log(`  Timing entries:`);
      timing.forEach(t => {
        const day = ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'][t.hotspot_timing_day || 0];
        const start = t.hotspot_start_time ? String(t.hotspot_start_time).substring(16,21) : 'N/A';
        const end = t.hotspot_end_time ? String(t.hotspot_end_time).substring(16,21) : 'N/A';
        console.log(`    ${day}: ${start} - ${end}`);
      });
    } else {
      console.log(`  ⚠️  Open 24/7 or no timing data`);
    }
  }

  await prisma.$disconnect();
}

debugRoute3Timing().catch(console.error);
