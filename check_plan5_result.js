const { PrismaClient } = require('@prisma/client');
const prisma = new PrismaClient();

async function checkPlan5Result() {
  console.log('\n=== CHECKING PLAN 5 RESULT ===\n');
  
  // Get Plan 5 routes
  const routes = await prisma.dvi_itinerary_route_details.findMany({
    where: { itinerary_plan_ID: 5, deleted: 0, status: 1 },
    orderBy: { itinerary_route_ID: 'asc' }
  });
  
  console.log(`Found ${routes.length} routes for Plan 5:\n`);
  
  for (const route of routes) {
    console.log(`\n📍 Route ${route.itinerary_route_ID}: ${route.location_name} → ${route.next_visiting_location}`);
    console.log(`   Date: ${route.itinerary_route_date?.toISOString().split('T')[0]}`);
    console.log(`   Time: ${route.route_start_time} to ${route.route_end_time}`);
    console.log(`   Direct: ${route.direct_to_next_visiting_place}`);
    
    // Get hotspots for this route
    const hotspots = await prisma.dvi_itinerary_route_hotspot_details.findMany({
      where: {
        itinerary_plan_ID: 5,
        itinerary_route_ID: route.itinerary_route_ID,
        deleted: 0,
        status: 1,
        item_type: { in: [3, 4] }
      },
      orderBy: { hotspot_order: 'asc' },
      include: {
        dvi_hotspot_place: {
          select: {
            hotspot_name: true,
            hotspot_priority: true,
            hotspot_location: true
          }
        }
      }
    });
    
    if (hotspots.length > 0) {
      console.log(`\n   Hotspots (${hotspots.length}):`);
      hotspots.forEach(h => {
        const typeLabel = h.item_type === 3 ? '🚗 Travel to' : '📍 Stay at';
        console.log(`   ${h.hotspot_order}. ${typeLabel} ${h.dvi_hotspot_place?.hotspot_name || 'N/A'}`);
        console.log(`      ID: ${h.hotspot_ID}, Priority: ${h.dvi_hotspot_place?.hotspot_priority || 'N/A'}`);
        console.log(`      Time: ${h.hotspot_start_time} - ${h.hotspot_end_time}`);
      });
    } else {
      console.log(`   ⚠️  No hotspots`);
    }
  }
  
  // Get all unique hotspots
  const allHotspots = await prisma.dvi_itinerary_route_hotspot_details.findMany({
    where: {
      itinerary_plan_ID: 5,
      deleted: 0,
      status: 1,
      item_type: 4 // Only stay items
    },
    include: {
      dvi_hotspot_place: {
        select: {
          hotspot_name: true,
          hotspot_priority: true
        }
      }
    }
  });
  
  console.log(`\n\n📊 SUMMARY:`);
  console.log(`Total hotspot stays: ${allHotspots.length}`);
  console.log(`Unique hotspots: ${new Set(allHotspots.map(h => h.hotspot_ID)).size}`);
  
  if (allHotspots.length > 0) {
    console.log(`\nAll hotspots in Plan 5:`);
    const uniqueHotspots = Array.from(new Set(allHotspots.map(h => h.hotspot_ID)))
      .map(id => allHotspots.find(h => h.hotspot_ID === id));
    
    uniqueHotspots.forEach(h => {
      console.log(`  - ${h.dvi_hotspot_place?.hotspot_name} (ID: ${h.hotspot_ID}, Priority: ${h.dvi_hotspot_place?.hotspot_priority})`);
    });
  }
  
  await prisma.$disconnect();
}

checkPlan5Result().catch(e => {
  console.error(e);
  process.exit(1);
});
