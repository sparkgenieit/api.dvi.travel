/**
 * Debug script to check if hotspot 41 was inserted correctly
 * and why it's not appearing in the timeline
 */

import { PrismaClient } from '@prisma/client';

const prisma = new PrismaClient();

async function debugHotspot41() {
  console.log('\n=== HOTSPOT 41 DEBUG ===\n');

  // 1. Check if hotspot 41 exists in master table
  const hotspot41 = await prisma.dvi_hotspot_place.findUnique({
    where: { hotspot_ID: 41 },
    select: {
      hotspot_ID: true,
      hotspot_location: true,
      hotspot_duration: true,
      status: true,
      deleted: true,
    },
  });

  console.log('1. Hotspot 41 in master table:');
  if (hotspot41) {
    console.log('   ✅ FOUND:', hotspot41);
  } else {
    console.log('   ❌ NOT FOUND - hotspot 41 does not exist!');
  }

  // 2. Check if hotspot 41 was inserted in route 348
  const assigned = await prisma.dvi_itinerary_route_hotspot_details.findMany({
    where: {
      itinerary_plan_ID: 17,
      itinerary_route_ID: 348,
      hotspot_ID: 41,
      deleted: 0,
    },
    select: {
      route_hotspot_ID: true,
      hotspot_ID: true,
      hotspot_plan_own_way: true,
      item_type: true,
      status: true,
      deleted: true,
      createdby: true,
      createdon: true,
    },
  });

  console.log('\n2. Hotspot 41 assignment to route 348:');
  if (assigned.length > 0) {
    console.log(`   ✅ FOUND ${assigned.length} record(s):`);
    assigned.forEach((r) => console.log('   ', r));
  } else {
    console.log('   ❌ NOT FOUND - hotspot 41 not assigned to route 348!');
  }

  // 3. Check timing records for hotspot 41
  const timings = await prisma.dvi_hotspot_timing.findMany({
    where: {
      hotspot_ID: 41,
      deleted: 0,
      status: 1,
    },
    select: {
      hotspot_ID: true,
      hotspot_timing_day: true,
      hotspot_start_time: true,
      hotspot_end_time: true,
      hotspot_closed: true,
      hotspot_open_all_time: true,
    },
  });

  console.log('\n3. Timing records for hotspot 41:');
  if (timings.length > 0) {
    console.log(`   ✅ FOUND ${timings.length} timing record(s):`);
    timings.forEach((t) => console.log('   ', t));
  } else {
    console.log('   ℹ️  NO TIMING RECORDS - hotspot 41 has no timing data (this is expected!)');
  }

  // 4. Check what hotspots ARE assigned to route 348
  const allAssigned = await prisma.dvi_itinerary_route_hotspot_details.findMany({
    where: {
      itinerary_plan_ID: 17,
      itinerary_route_ID: 348,
      item_type: 4,
      deleted: 0,
    },
    select: {
      hotspot_ID: true,
      hotspot_plan_own_way: true,
    },
  });

  console.log('\n4. All hotspots (item_type=4) assigned to route 348:');
  console.log(`   Found ${allAssigned.length} hotspot(s):`);
  allAssigned.forEach((h) => {
    console.log(`   - Hotspot ${h.hotspot_ID}${h.hotspot_plan_own_way === 1 ? ' (manual)' : ''}`);
  });

  // 5. Check if hotspot 41 location matches route 348 selection criteria
  const route348 = await prisma.dvi_itinerary_route_details.findFirst({
    where: {
      itinerary_plan_ID: 17,
      itinerary_route_ID: 348,
    },
    select: {
      location_name: true,
      next_visiting_location: true,
    },
  });

  console.log('\n5. Route 348 location info:');
  console.log('   Location:', route348?.location_name);
  console.log('   Next location:', route348?.next_visiting_location);
  console.log('   Hotspot 41 location:', hotspot41?.hotspot_location);

  // 6. Check for ANY timing records in route 348's date
  const route348Full = await prisma.dvi_itinerary_route_details.findFirst({
    where: {
      itinerary_plan_ID: 17,
      itinerary_route_ID: 348,
    },
    select: {
      itinerary_route_date: true,
    },
  });

  if (route348Full?.itinerary_route_date) {
    const jsDay = new Date(route348Full.itinerary_route_date).getDay();
    const phpDay = (jsDay + 6) % 7;
    console.log('\n6. Route 348 date analysis:');
    console.log('   Date:', route348Full.itinerary_route_date);
    console.log('   JS day of week:', jsDay);
    console.log('   PHP day of week:', phpDay);

    // Check what hotspots HAVE timing for this day
    const timingsForDay = await prisma.dvi_hotspot_timing.findMany({
      where: {
        hotspot_timing_day: phpDay,
        deleted: 0,
        status: 1,
      },
      select: {
        hotspot_ID: true,
        hotspot_timing_day: true,
      },
      distinct: ['hotspot_ID'],
    });

    console.log(
      `   Hotspots with timing for day ${phpDay}: ${timingsForDay.length} hotspots`
    );
    console.log(
      '   IDs:',
      timingsForDay
        .map((t) => t.hotspot_ID)
        .sort((a, b) => a - b)
        .join(', ')
    );
  }

  console.log('\n=== END DEBUG ===\n');

  await prisma.$disconnect();
}

debugHotspot41().catch(console.error);
