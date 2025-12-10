const { PrismaClient } = require('@prisma/client');
const prisma = new PrismaClient();

async function analyzeHotspot18Timing() {
  console.log('\n=== ANALYZING HOTSPOT 18 (AUROVILLE) TIMING ISSUE ===\n');
  
  // Get hotspot 18 details
  const hotspot = await prisma.dvi_hotspot_place.findUnique({
    where: { hotspot_ID: 18 },
    select: {
      hotspot_ID: true,
      hotspot_name: true,
      hotspot_priority: true,
      hotspot_location: true,
      hotspot_duration: true,
    }
  });
  
  console.log('Hotspot 18 Details:');
  console.log(`  ID: ${hotspot.hotspot_ID}`);
  console.log(`  Name: ${hotspot.hotspot_name}`);
  console.log(`  Priority: ${hotspot.hotspot_priority}`);
  console.log(`  Location: ${hotspot.hotspot_location}`);
  console.log(`  Duration: ${hotspot.hotspot_duration}`);
  
  // Get timing windows for hotspot 18
  const timings = await prisma.dvi_hotspot_timing.findMany({
    where: { 
      hotspot_ID: 18,
      deleted: 0,
      status: 1
    },
    orderBy: { hotspot_timing_day: 'asc' }
  });
  
  console.log('\n=== OPERATING HOURS (All Days) ===');
  const days = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
  timings.forEach(t => {
    const dayName = days[t.hotspot_timing_day] || `Day ${t.hotspot_timing_day}`;
    console.log(`  ${dayName} (${t.hotspot_timing_day}): ${t.hotspot_start_time} - ${t.hotspot_end_time}`);
  });
  
  // Check Route 2 from Plan 2
  const plan2Routes = await prisma.dvi_itinerary_route_details.findMany({
    where: { itinerary_plan_ID: 2, deleted: 0, status: 1 },
    orderBy: { itinerary_route_ID: 'asc' },
  });
  
  const route2 = plan2Routes[1];
  console.log('\n=== PHP ROUTE 2 (Plan 2) ===');
  console.log(`  Route ID: ${route2.itinerary_route_ID}`);
  console.log(`  ${route2.target_location} → ${route2.next_location}`);
  console.log(`  Route day: ${route2.route_day}`);
  console.log(`  Start time: ${route2.route_start_time}`);
  console.log(`  End time: ${route2.route_end_time}`);
  
  // Get PHP's actual hotspot 18 timing from timeline
  const phpHotspot18 = await prisma.dvi_itinerary_route_hotspot_details.findFirst({
    where: {
      itinerary_plan_ID: 2,
      itinerary_route_ID: route2.itinerary_route_ID,
      hotspot_ID: 18
    }
  });
  
  if (phpHotspot18) {
    console.log('\n=== PHP SELECTED HOTSPOT 18 ===');
    console.log(`  ✅ PHP DID select hotspot 18`);
    console.log(`  Start time: ${phpHotspot18.hotspot_start_time}`);
    console.log(`  End time: ${phpHotspot18.hotspot_end_time}`);
    console.log(`  Duration: ${phpHotspot18.hotspot_traveling_time}`);
    console.log(`  Item type: ${phpHotspot18.item_type}`);
    console.log(`  Order: ${phpHotspot18.hotspot_order}`);
  } else {
    console.log('\n=== PHP SELECTED HOTSPOT 18 ===');
    console.log(`  ❌ PHP did NOT select hotspot 18`);
  }
  
  // Get NestJS Route 2
  const plan5Routes = await prisma.dvi_itinerary_route_details.findMany({
    where: { itinerary_plan_ID: 5, deleted: 0, status: 1 },
    orderBy: { itinerary_route_ID: 'asc' },
  });
  
  const nestRoute2 = plan5Routes[1];
  console.log('\n=== NESTJS ROUTE 2 (Plan 5) ===');
  console.log(`  Route ID: ${nestRoute2.itinerary_route_ID}`);
  console.log(`  ${nestRoute2.target_location} → ${nestRoute2.next_location}`);
  console.log(`  Route day: ${nestRoute2.route_day}`);
  
  const nestHotspot18 = await prisma.dvi_itinerary_route_hotspot_details.findFirst({
    where: {
      itinerary_plan_ID: 5,
      itinerary_route_ID: nestRoute2.itinerary_route_ID,
      hotspot_ID: 18
    }
  });
  
  if (nestHotspot18) {
    console.log(`  ✅ NestJS selected hotspot 18`);
    console.log(`  Start time: ${nestHotspot18.hotspot_start_time}`);
    console.log(`  End time: ${nestHotspot18.hotspot_end_time}`);
  } else {
    console.log(`  ❌ NestJS did NOT select hotspot 18`);
  }
  
  // Analysis
  console.log('\n=== ANALYSIS ===');
  console.log('NestJS Log said:');
  console.log('  Visit window: 13:19:00-20:49:00 on day 3');
  console.log('  Timing window 1: 09:00:00-13:00:00');
  console.log('  Timing window 2: 13:30:00-17:00:00');
  console.log('  Result: "not open during visit window"');
  console.log('');
  console.log('Issue: Visit starts at 13:19 which falls in the GAP (13:00-13:30)');
  console.log('');
  console.log('PHP must be using different logic:');
  console.log('  1. Maybe PHP checks if visit END overlaps with ANY timing window?');
  console.log('  2. Or PHP has more lenient timing checks?');
  console.log('  3. Or PHP ignores timing for certain hotspots/routes?');
  
  await prisma.$disconnect();
}

analyzeHotspot18Timing().catch(console.error);
