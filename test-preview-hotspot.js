const { PrismaClient } = require('@prisma/client');

const prisma = new PrismaClient();

async function testPreviewHotspot() {
  try {
    console.log('\n=== Testing previewManualHotspotAdd ===\n');
    
    const planId = 33977;
    const routeId = 5; // Rameswaram
    const hotspotId = 34; // Some hotspot to test
    
    console.log(`Plan: ${planId}`);
    console.log(`Route: ${routeId} (Rameswaram)`);
    console.log(`Hotspot: ${hotspotId}\n`);
    
    // Fetch existing hotspots for this plan
    const existing = await prisma.dvi_itinerary_route_hotspot_details.findMany({
      where: {
        itinerary_plan_ID: planId,
        item_type: 4,
        deleted: 0
      }
    });
    
    console.log(`Existing hotspots for plan ${planId}: ${existing.length}`);
    existing.forEach(h => {
      console.log(`  - Route ${h.itinerary_route_ID}: Hotspot ${h.hotspot_ID} (manual: ${h.hotspot_plan_own_way})`);
    });
    
    // Now test the API - we'll simulate what the endpoint does
    console.log(`\nTesting preview add of hotspot ${hotspotId} to route ${routeId}...\n`);
    
    const augmented = [...existing];
    augmented.push({
      itinerary_plan_ID: planId,
      itinerary_route_ID: routeId,
      hotspot_ID: hotspotId,
      hotspot_plan_own_way: 1, // Manual
      status: 1,
      deleted: 0,
    });
    
    console.log(`Total hotspots after adding: ${augmented.length}`);
    console.log(`Test setup complete. Backend would now call buildTimelineForPlan with augmented list.\n`);
    
  } catch (error) {
    console.error('Error:', error);
  } finally {
    await prisma.$disconnect();
  }
}

testPreviewHotspot();
