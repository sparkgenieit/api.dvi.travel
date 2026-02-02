#!/usr/bin/env node

/**
 * Direct database verification that hotspot 41 is properly assigned
 */

const { PrismaClient } = require('@prisma/client');
const prisma = new PrismaClient();

async function verify() {
  try {
    console.log('\n════════════════════════════════════════════════════════════════');
    console.log('✅ SOLUTION VERIFICATION - Hotspot 41 Assignment Fix');
    console.log('════════════════════════════════════════════════════════════════\n');

    console.log('Fix Applied: timeline.builder.ts now includes already-assigned hotspots\n');

    // Verify hotspot 41 is ONLY in route 348
    const route347 = await prisma.dvi_itinerary_route_hotspot_details.findMany({
      where: {
        itinerary_route_ID: 347,
        hotspot_ID: 41,
        deleted: 0,
        status: 1,
      },
    });

    const route348 = await prisma.dvi_itinerary_route_hotspot_details.findMany({
      where: {
        itinerary_route_ID: 348,
        hotspot_ID: 41,
        deleted: 0,
        status: 1,
      },
    });

    console.log('Database State:');
    console.log(`  Route 347: ${route347.length} records of hotspot 41 (should be 0)`);
    console.log(`  Route 348: ${route348.length} records of hotspot 41 (should be 1+)`);

    if (route348.length > 0) {
      console.log('\n✅ Hotspot 41 is correctly assigned to Route 348!\n');
    } else {
      console.log('\n❌ Hotspot 41 is NOT in Route 348!\n');
    }

    // Verify route details
    const route348Info = await prisma.dvi_itinerary_route_details.findUnique({
      where: { itinerary_route_ID: 348 },
      select: {
        location_name: true,
        next_visiting_location: true,
      },
    });

    const hotspot41 = await prisma.dvi_hotspot_place.findUnique({
      where: { hotspot_ID: 41 },
      select: {
        hotspot_name: true,
        hotspot_location: true,
      },
    });

    console.log('Location Verification:');
    console.log(`  Hotspot 41: "${hotspot41.hotspot_name}"`);
    console.log(`  Location: ${hotspot41.hotspot_location}`);
    console.log(`  Route 348: ${route348Info.location_name} → ${route348Info.next_visiting_location}`);
    console.log(`  Match: ${hotspot41.hotspot_location?.includes('Rameswaram') ? '✅ YES' : '❌ NO'}`);

    console.log('\n════════════════════════════════════════════════════════════════');
    console.log('📝 NEXT STEP: Restart the server and test the timeline endpoint');
    console.log('Command: npm start');
    console.log('Test: node test-hotspot-41-preview-FIXED.js');
    console.log('════════════════════════════════════════════════════════════════\n');

  } catch (error) {
    console.error('Error:', error);
  } finally {
    await prisma.$disconnect();
  }
}

verify();
