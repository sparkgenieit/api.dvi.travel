import { PrismaClient } from '@prisma/client';

const prisma = new PrismaClient();

async function debug() {
  try {
    console.log('\n🔍 DEBUG: Checking database state after preview-add\n');

    // Check what hotspots are assigned to route 348
    const route348Hotspots = await (prisma as any).dvi_itinerary_route_hotspot_details.findMany({
      where: {
        itinerary_route_ID: 348,
        deleted: 0,
        status: 1,
      },
      select: {
        hotspot_ID: true,
        item_type: true,
        hotspot_plan_own_way: true,
      },
      orderBy: { hotspot_ID: 'asc' },
    });

    console.log(`Route 348 has ${route348Hotspots.length} hotspots:`);
    const hotspotIds = route348Hotspots.map(h => h.hotspot_ID);
    console.log(`  IDs: ${hotspotIds.join(', ')}`);

    if (hotspotIds.includes(41)) {
      console.log('\n✅ Hotspot 41 IS in database for route 348');
      const hs41 = route348Hotspots.find(h => h.hotspot_ID === 41);
      console.log(`   item_type: ${hs41?.item_type}`);
      console.log(`   hotspot_plan_own_way: ${hs41?.hotspot_plan_own_way}`);
    } else {
      console.log('\n❌ Hotspot 41 NOT in database for route 348');
    }

    // Get the hotspot master data
    const hs41Master = await (prisma as any).dvi_hotspot_place.findUnique({
      where: { hotspot_ID: 41 },
      select: {
        hotspot_ID: true,
        hotspot_name: true,
        hotspot_location: true,
      },
    });

    console.log(`\nHotspot 41 master:`);
    console.log(`  Name: ${hs41Master?.hotspot_name}`);
    console.log(`  Location: ${hs41Master?.hotspot_location}`);

    // Get route 348 details
    const route348 = await (prisma as any).dvi_itinerary_route_details.findUnique({
      where: { itinerary_route_ID: 348 },
      select: {
        location_name: true,
        next_visiting_location: true,
      },
    });

    console.log(`\nRoute 348:`);
    console.log(`  Location: ${route348?.location_name}`);
    console.log(`  Next: ${route348?.next_visiting_location}`);

  } catch (error) {
    console.error('Error:', error);
  } finally {
    await prisma.$disconnect();
  }
}

debug();
