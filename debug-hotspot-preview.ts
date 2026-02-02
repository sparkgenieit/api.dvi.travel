import { PrismaClient } from '@prisma/client';

const prisma = new PrismaClient();

async function debugHotspotPreview() {
  try {
    const planId = 17;
    const routeId = 347;
    const hotspotId = 41;

    console.log('\n═══════════════════════════════════════════════════════════════');
    console.log('🔍 HOTSPOT PREVIEW DEBUG - Check Hotspot 41');
    console.log('═══════════════════════════════════════════════════════════════\n');

    // 1) Check if hotspot 41 exists in master table
    console.log('1️⃣  CHECKING MASTER HOTSPOT (hotspot_ID = 41)');
    const hotspot = await prisma.dvi_hotspot_place.findUnique({
      where: { hotspot_ID: 41 },
    });

    if (hotspot) {
      console.log('✅ Hotspot Found:');
      console.log(`   Name: ${hotspot.hotspot_name}`);
      console.log(`   Location: ${hotspot.hotspot_location}`);
      console.log(`   Status: ${hotspot.status}`);
      console.log(`   Deleted: ${hotspot.deleted}`);
    } else {
      console.log('❌ Hotspot 41 NOT FOUND in dvi_hotspot_place');
    }

    // 2) Check if hotspot 41 is currently in route 347
    console.log('\n2️⃣  CHECKING CURRENT ROUTE HOTSPOTS (planId=17, routeId=347)');
    const routeHotspots = await prisma.dvi_itinerary_route_hotspot_details.findMany({
      where: {
        itinerary_plan_ID: planId,
        itinerary_route_ID: routeId,
        deleted: 0,
      },
      orderBy: { hotspot_order: 'asc' },
    });

    console.log(`Found ${routeHotspots.length} hotspots:`);
    for (const rh of routeHotspots) {
      console.log(`   - ID: ${rh.hotspot_ID}, Order: ${rh.hotspot_order}, Type: ${rh.item_type}, Manual: ${rh.hotspot_plan_own_way}`);
    }

    // 3) Check if hotspot 41 exists with any item_type
    console.log('\n3️⃣  SEARCHING FOR HOTSPOT 41 IN THIS PLAN/ROUTE (ANY STATE)');
    const hotspot41InRoute = await prisma.dvi_itinerary_route_hotspot_details.findMany({
      where: {
        itinerary_plan_ID: planId,
        itinerary_route_ID: routeId,
        hotspot_ID: hotspotId,
      },
    });

    if (hotspot41InRoute.length > 0) {
      console.log(`✅ Found ${hotspot41InRoute.length} record(s) for hotspot 41:`);
      for (const rec of hotspot41InRoute) {
        console.log(`   Status: ${rec.status}, Deleted: ${rec.deleted}, ItemType: ${rec.item_type}, Order: ${rec.hotspot_order}`);
      }
    } else {
      console.log('❌ Hotspot 41 NOT found in route 347 at all');
    }

    // 4) Check all hotspots in plan 17 to see what's available
    console.log('\n4️⃣  ALL HOTSPOTS IN PLAN 17 (ALL ROUTES)');
    const allPlanHotspots = await prisma.dvi_itinerary_route_hotspot_details.findMany({
      where: {
        itinerary_plan_ID: planId,
        deleted: 0,
      },
      select: {
        hotspot_ID: true,
        itinerary_route_ID: true,
        item_type: true,
        hotspot_order: true,
      },
      distinct: ['hotspot_ID'],
    });

    const uniqueHotspots = new Set(allPlanHotspots.map(h => h.hotspot_ID));
    console.log(`Total unique hotspots in plan: ${uniqueHotspots.size}`);
    console.log(`Hotspot IDs: ${Array.from(uniqueHotspots).sort((a, b) => a - b).join(', ')}`);

    // 5) Check hotspot locations for route 347
    console.log('\n5️⃣  HOTSPOT DETAILS FOR ROUTE 347 (WITH NAMES)');
    const routeHotspotsWithNames = await prisma.dvi_itinerary_route_hotspot_details.findMany({
      where: {
        itinerary_plan_ID: planId,
        itinerary_route_ID: routeId,
        deleted: 0,
        item_type: 4, // Only actual hotspot visits
      },
    });

    console.log(`Found ${routeHotspotsWithNames.length} hotspot attractions:`);
    for (const rh of routeHotspotsWithNames) {
      // Get hotspot name separately
      const hsDetails = await prisma.dvi_hotspot_place.findUnique({
        where: { hotspot_ID: rh.hotspot_ID },
        select: { hotspot_name: true },
      });
      const name = hsDetails?.hotspot_name || 'N/A';
      console.log(`   - ID: ${rh.hotspot_ID}, Name: ${name}, Order: ${rh.hotspot_order}`);
    }

    // 6) Check if hotspot 41 is used anywhere in the system
    console.log('\n6️⃣  CHECKING IF HOTSPOT 41 IS USED IN ANY PLAN');
    const hotspot41Usage = await prisma.dvi_itinerary_route_hotspot_details.findMany({
      where: {
        hotspot_ID: 41,
        deleted: 0,
      },
      take: 5,
    });

    if (hotspot41Usage.length > 0) {
      console.log(`✅ Hotspot 41 is used in ${hotspot41Usage.length} places:`);
      for (const usage of hotspot41Usage) {
        console.log(`   - Plan: ${usage.itinerary_plan_ID}, Route: ${usage.itinerary_route_ID}, Type: ${usage.item_type}`);
      }
    } else {
      console.log('❌ Hotspot 41 is not used in any plan');
    }

    // 7) Check route 347 location info
    console.log('\n7️⃣  ROUTE 347 DETAILS');
    const route = await prisma.dvi_itinerary_route_details.findFirst({
      where: { itinerary_route_ID: routeId },
    });

    if (route) {
      console.log(`Location: ${route.location_name}`);
      console.log(`Next Visiting: ${route.next_visiting_location}`);
      console.log(`Date: ${route.itinerary_route_date}`);
    }

    // 8) Check if hotspot 41 is suitable for this route's location
    console.log('\n8️⃣  CHECKING HOTSPOT 41 LOCATION COMPATIBILITY');
    if (hotspot && route) {
      const hotspotLocation = String(hotspot.hotspot_location || '').split('|');
      const routeLocation = String(route.location_name || '').trim();
      const nextLocation = String(route.next_visiting_location || '').split('|')[0].trim();

      console.log(`Hotspot locations: [${hotspotLocation.join(', ')}]`);
      console.log(`Route location: ${routeLocation}`);
      console.log(`Route next location: ${nextLocation}`);

      const matches = hotspotLocation.filter(h => h.trim().toLowerCase() === routeLocation.toLowerCase()).length > 0;
      console.log(`Match: ${matches ? '✅ YES' : '❌ NO'}`);
    }

    console.log('\n═══════════════════════════════════════════════════════════════\n');
  } catch (error) {
    console.error('❌ Error:', error);
  } finally {
    await prisma.$disconnect();
  }
}

debugHotspotPreview();
