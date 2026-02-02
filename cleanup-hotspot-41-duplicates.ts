/**
 * Clean up duplicate hotspot 41 records from route 348
 * Keep only the MOST RECENT one
 */

import { PrismaClient } from '@prisma/client';

const prisma = new PrismaClient();

async function cleanupHotspot41Duplicates() {
  console.log('\n=== CLEANING UP HOTSPOT 41 DUPLICATES ===\n');

  // Get all hotspot 41 records for route 348
  const duplicates = await prisma.dvi_itinerary_route_hotspot_details.findMany({
    where: {
      itinerary_plan_ID: 17,
      itinerary_route_ID: 348,
      hotspot_ID: 41,
      deleted: 0,
      item_type: 4,
    },
    orderBy: { createdby: 'desc' }, // This won't work, use createdon instead
  });

  console.log(`Found ${duplicates.length} hotspot 41 records for route 348:`);

  // Sort by createdby descending to get most recent
  duplicates.sort(
    (a, b) =>
      (b.createdon?.getTime() || 0) - (a.createdon?.getTime() || 0)
  );

  duplicates.forEach((d, i) => {
    const created = d.createdon ? d.createdon.toISOString() : 'unknown';
    console.log(`  ${i + 1}. ID=${d.route_hotspot_ID}, created=${created}`);
  });

  if (duplicates.length <= 1) {
    console.log('\nNo duplicates to clean up!');
    await prisma.$disconnect();
    return;
  }

  // Keep the MOST RECENT one (index 0 after sort)
  const keepId = duplicates[0].route_hotspot_ID;
  const deleteIds = duplicates.slice(1).map((d) => d.route_hotspot_ID);

  console.log(`\nKeeping record ID: ${keepId}`);
  console.log(`Soft-deleting records: ${deleteIds.join(', ')}`);

  // Soft-delete duplicates
  const result = await prisma.dvi_itinerary_route_hotspot_details.updateMany({
    where: {
      route_hotspot_ID: { in: deleteIds },
    },
    data: {
      deleted: 1,
    },
  });

  console.log(`\n✅ Soft-deleted ${result.count} duplicate records`);

  // Verify
  const remaining = await prisma.dvi_itinerary_route_hotspot_details.findMany({
    where: {
      itinerary_plan_ID: 17,
      itinerary_route_ID: 348,
      hotspot_ID: 41,
      deleted: 0,
    },
  });

  console.log(`Remaining active hotspot 41 records: ${remaining.length}`);
  if (remaining.length > 0) {
    console.log('  Record:', remaining[0].route_hotspot_ID);
  }

  console.log('\n=== CLEANUP COMPLETE ===\n');

  await prisma.$disconnect();
}

cleanupHotspot41Duplicates().catch(console.error);
