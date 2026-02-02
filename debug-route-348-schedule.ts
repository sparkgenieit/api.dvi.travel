/**
 * Check timeline building for route 348 specifically
 * to see why hotspot 41 is not appearing
 */

import { PrismaClient } from '@prisma/client';

const prisma = new PrismaClient();

async function debugRoute348Timeline() {
  console.log('\n=== ROUTE 348 TIMELINE ANALYSIS ===\n');

  // Get route details
  const route = await prisma.dvi_itinerary_route_details.findFirst({
    where: {
      itinerary_plan_ID: 17,
      itinerary_route_ID: 348,
    },
  });

  console.log('Route 348 Details:');
  console.log('  Start time:', route?.route_start_time);
  console.log('  End time:', route?.route_end_time);
  console.log('  Date:', route?.itinerary_route_date);
  console.log('  From:', route?.location_name);
  console.log('  To:', route?.next_visiting_location);

  // Get the already-inserted hotspot rows to understand the schedule
  const existingHotspots = await prisma.dvi_itinerary_route_hotspot_details.findMany({
    where: {
      itinerary_plan_ID: 17,
      itinerary_route_ID: 348,
      deleted: 0,
      status: 1,
    },
    orderBy: { hotspot_order: 'asc' },
    select: {
      route_hotspot_ID: true,
      hotspot_ID: true,
      item_type: true,
      hotspot_order: true,
      hotspot_start_time: true,
      hotspot_end_time: true,
      hotspot_plan_own_way: true,
    },
  });

  console.log('\nExisting hotspots (sorted by order):');
  existingHotspots.slice(0, 10).forEach((h) => {
    const start = h.hotspot_start_time ? new Date(h.hotspot_start_time).toLocaleTimeString() : 'N/A';
    const end = h.hotspot_end_time ? new Date(h.hotspot_end_time).toLocaleTimeString() : 'N/A';
    const itemType = h.item_type === 4 ? 'ATTRACTION' : `TYPE_${h.item_type}`;
    const isManual = h.hotspot_plan_own_way === 1 ? ' [MANUAL]' : '';
    console.log(
      `  Order ${h.hotspot_order}: Hotspot ${h.hotspot_ID || 0} (${itemType})${isManual} - ${start} to ${end}`
    );
  });

  // Get hotspot 41 timing for day 0
  const hotspot41Timings = await prisma.dvi_hotspot_timing.findMany({
    where: {
      hotspot_ID: 41,
      hotspot_timing_day: 0,
      deleted: 0,
      status: 1,
    },
  });

  console.log('\nHotspot 41 timing for day 0 (Sunday):');
  hotspot41Timings.forEach((t) => {
    const start = new Date(t.hotspot_start_time).toLocaleTimeString();
    const end = new Date(t.hotspot_end_time).toLocaleTimeString();
    console.log(`  ${start} - ${end}${t.hotspot_closed === 1 ? ' [CLOSED]' : ''}`);
  });

  // Check the last attraction time in schedule
  const lastAttraction = existingHotspots.find(
    (h) => h.item_type === 4 && h.hotspot_ID !== 0 && h.hotspot_ID !== 41
  );
  if (lastAttraction) {
    const endTime = lastAttraction.hotspot_end_time
      ? new Date(lastAttraction.hotspot_end_time).toLocaleTimeString()
      : 'N/A';
    console.log('\nLast attraction before hotspot 41 would be added:');
    console.log(`  Hotspot ${lastAttraction.hotspot_ID} ends at ${endTime}`);
  }

  // Check if there's a time gap where hotspot 41 could fit
  console.log('\nTime analysis:');
  console.log('  Hotspot 41 operates: 9:00 AM - 5:00 PM');
  console.log('  Hotspot 41 duration: 45 minutes');
  console.log('  Required window: 45 minutes within 9 AM - 5 PM');

  const lastAttTime = lastAttraction?.hotspot_end_time
    ? new Date(lastAttraction.hotspot_end_time).getTime()
    : 0;
  const route9AMMs = new Date(route?.itinerary_route_date as Date).setHours(9, 0, 0, 0);
  const route5PMMs = new Date(route?.itinerary_route_date as Date).setHours(17, 0, 0, 0);

  if (lastAttTime > 0) {
    const timeUntil5PM = (route5PMMs - lastAttTime) / (1000 * 60);
    console.log(`  Time remaining until 5 PM: ${Math.floor(timeUntil5PM)} minutes`);

    if (timeUntil5PM < 45) {
      console.log(`  ❌ NOT ENOUGH TIME for 45-minute hotspot 41!`);
    } else {
      console.log(`  ✅ Enough time available`);
    }
  }

  // Check what the issue might be
  console.log('\nPossible reasons hotspot 41 is missing:');
  console.log('  1. All duplicate copies have same timing, so ONE passes filter');
  console.log('  2. That ONE copy gets deduplicated out');
  console.log('  3. OR: Visit time does NOT fit within 9 AM - 5 PM window');
  console.log('  4. OR: Travel time prevents adding another hotspot');

  await prisma.$disconnect();
}

debugRoute348Timeline().catch(console.error);
