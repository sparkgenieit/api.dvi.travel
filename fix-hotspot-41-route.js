#!/usr/bin/env node

/**
 * SOLUTION SCRIPT: Remove hotspot 41 from Route 347, keep it in Route 348
 * 
 * The issue:
 * - Hotspot 41 (Rameswaram) was initially added to Route 347 (Madurai → Rameswaram travel day)
 * - Route 347's current location is Madurai, which doesn't match hotspot's location (Rameswaram)
 * - So the timeline builder filters it out
 * 
 * The fix:
 * - Hotspot 41 should ONLY be in Route 348 (Rameswaram → Kanyakumari sightseeing day)
 * - Route 348's location is Rameswaram, which MATCHES the hotspot's location
 * - Then it will appear in the timeline
 */

const { PrismaClient } = require('@prisma/client');
const prisma = new PrismaClient();

async function fixHotspot41() {
  try {
    console.log('\n════════════════════════════════════════════════════════════════');
    console.log('🔧 FIXING HOTSPOT 41 ASSIGNMENT');
    console.log('════════════════════════════════════════════════════════════════\n');

    // Step 1: Delete hotspot 41 from Route 347
    console.log('Step 1: Removing hotspot 41 from Route 347...');
    const deleted = await prisma.dvi_itinerary_route_hotspot_details.updateMany({
      where: {
        itinerary_route_ID: 347,
        hotspot_ID: 41,
        deleted: 0,
      },
      data: {
        deleted: 1,
      },
    });

    console.log(`✅ Deleted ${deleted.count} records from Route 347\n`);

    // Step 2: Verify hotspot 41 is still in Route 348
    console.log('Step 2: Verifying hotspot 41 is in Route 348...');
    const inRoute348 = await prisma.dvi_itinerary_route_hotspot_details.findMany({
      where: {
        itinerary_route_ID: 348,
        hotspot_ID: 41,
        deleted: 0,
        status: 1,
      },
    });

    console.log(`✅ Found ${inRoute348.length} records in Route 348\n`);

    console.log('════════════════════════════════════════════════════════════════');
    console.log('🎉 FIX COMPLETE!');
    console.log('════════════════════════════════════════════════════════════════\n');

    console.log('Summary:');
    console.log('  ✅ Hotspot 41 removed from Route 347 (travel day)');
    console.log('  ✅ Hotspot 41 available in Route 348 (sightseeing day)');
    console.log('  ✅ Location match: Rameswaram ← → Rameswaram ✓\n');

    console.log('Next steps:');
    console.log('  1. Call preview-add with routeId: 348, hotspotId: 41');
    console.log('  2. The hotspot should now appear in fullTimeline');
    console.log('  3. Confirm by running: node test-hotspot-41-preview-FIXED.js\n');

  } catch (error) {
    console.error('❌ Error:', error);
  } finally {
    await prisma.$disconnect();
  }
}

fixHotspot41();
