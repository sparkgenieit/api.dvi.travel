const { PrismaClient } = require('@prisma/client');
const prisma = new PrismaClient();

async function checkRoute211() {
  try {
    console.log('=== CHECKING ROUTE 211 HOTSPOTS ===\n');
    
    const hotspots = await prisma.dvi_itinerary_route_hotspot_details.findMany({
      where: {
        itinerary_plan_ID: 5,
        itinerary_route_ID: 211,
        deleted: 0,
        status: 1,
        item_type: { in: [3, 4] }
      },
      orderBy: { hotspot_order: 'asc' }
    });
    
    console.log(`Found ${hotspots.length} hotspot rows (item_type 3 & 4)\n`);
    
    hotspots.forEach(h => {
      console.log(`Order ${h.hotspot_order}: item_type=${h.item_type} (${h.item_type === 3 ? 'travel' : 'stay'}) hotspot_ID=${h.hotspot_ID}`);
    });
    
    // Get unique hotspot IDs
    const uniqueHotspotIds = [...new Set(hotspots.map(h => h.hotspot_ID))];
    console.log(`\nUnique hotspot IDs: ${uniqueHotspotIds.join(', ')}`);
    
    // Now compare with PHP Route 178
    console.log('\n=== COMPARING WITH PHP ROUTE 178 ===\n');
    
    const phpHotspots = await prisma.dvi_itinerary_route_hotspot_details.findMany({
      where: {
        itinerary_plan_ID: 2,
        itinerary_route_ID: 178,
        deleted: 0,
        status: 1,
        item_type: { in: [3, 4] }
      },
      orderBy: { hotspot_order: 'asc' }
    });
    
    console.log(`PHP Route 178 has ${phpHotspots.length} hotspot rows\n`);
    
    phpHotspots.forEach(h => {
      console.log(`Order ${h.hotspot_order}: item_type=${h.item_type} hotspot_ID=${h.hotspot_ID}`);
    });
    
    const phpUniqueIds = [...new Set(phpHotspots.map(h => h.hotspot_ID))];
    console.log(`\nPHP unique hotspot IDs: ${phpUniqueIds.join(', ')}`);
    
    console.log('\n=== MATCH STATUS ===');
    const match = JSON.stringify(uniqueHotspotIds.sort()) === JSON.stringify(phpUniqueIds.sort());
    console.log(match ? '✅ HOTSPOTS MATCH!' : '❌ HOTSPOTS DO NOT MATCH');
    
  } catch (error) {
    console.error('Error:', error.message);
  } finally {
    await prisma.$disconnect();
  }
}

checkRoute211();
