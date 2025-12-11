const { PrismaClient } = require('@prisma/client');
const prisma = new PrismaClient();

async function checkHotspotDuplication() {
  console.log('\n=== CHECKING PHP HOTSPOT DUPLICATION BEHAVIOR ===\n');
  
  const plan2Hotspots = await prisma.dvi_itinerary_route_hotspot_details.findMany({
    where: {
      itinerary_plan_ID: 2,
      item_type: 4,
      deleted: 0
    },
    orderBy: [
      { itinerary_route_ID: 'asc' },
      { hotspot_order: 'asc' }
    ]
  });
  
  console.log(`Total hotspot visits in Plan 2: ${plan2Hotspots.length}\n`);
  
  const hotspotCounts = {};
  const hotspotRoutes = {};
  
  for (const h of plan2Hotspots) {
    const id = h.hotspot_ID;
    hotspotCounts[id] = (hotspotCounts[id] || 0) + 1;
    if (!hotspotRoutes[id]) {
      hotspotRoutes[id] = [];
    }
    hotspotRoutes[id].push(h.itinerary_route_ID);
  }
  
  const duplicates = Object.entries(hotspotCounts).filter(([id, count]) => count > 1);
  
  if (duplicates.length > 0) {
    console.log(`✅ PHP ALLOWS DUPLICATE HOTSPOTS! Found ${duplicates.length} hotspots used multiple times:\n`);
    
    for (const [id, count] of duplicates) {
      const hotspot = await prisma.dvi_hotspot_place.findUnique({
        where: { hotspot_ID: parseInt(id) }
      });
      console.log(`  Hotspot ${id}: "${hotspot?.hotspot_name}"`);
      console.log(`    Used ${count} times in routes: ${hotspotRoutes[id].join(', ')}`);
    }
  } else {
    console.log('❌ No duplicate hotspots found in Plan 2');
  }
  
  await prisma.$disconnect();
}

checkHotspotDuplication();
