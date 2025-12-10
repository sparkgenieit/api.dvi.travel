const { PrismaClient } = require('@prisma/client');
const prisma = new PrismaClient();

async function compareFinal() {
  try {
    console.log('=== FINAL COMPARISON ===\n');
    
    // PHP Route 178
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
    
    const phpIds = [...new Set(phpHotspots.map(h => h.hotspot_ID))];
    console.log('PHP Route 178:');
    console.log('  Hotspot rows:', phpHotspots.length);
    console.log('  Unique hotspot IDs:', phpIds.join(', '));
    phpHotspots.forEach(h => {
      console.log(`    Order ${h.hotspot_order}: item_type=${h.item_type} hotspot_ID=${h.hotspot_ID}`);
    });
    
    // NestJS Route 217
    const nestHotspots = await prisma.dvi_itinerary_route_hotspot_details.findMany({
      where: {
        itinerary_plan_ID: 5,
        itinerary_route_ID: 217,
        deleted: 0,
        status: 1,
        item_type: { in: [3, 4] }
      },
      orderBy: { hotspot_order: 'asc' }
    });
    
    const nestIds = [...new Set(nestHotspots.map(h => h.hotspot_ID))];
    console.log('\nNestJS Route 217:');
    console.log('  Hotspot rows:', nestHotspots.length);
    console.log('  Unique hotspot IDs:', nestIds.join(', '));
    nestHotspots.forEach(h => {
      console.log(`    Order ${h.hotspot_order}: item_type=${h.item_type} hotspot_ID=${h.hotspot_ID}`);
    });
    
    console.log('\n=== MATCH STATUS ===');
    const match = JSON.stringify(phpIds.sort()) === JSON.stringify(nestIds.sort());
    if (match) {
      console.log('✅ PERFECT MATCH! Both routes have identical hotspots.');
    } else {
      console.log('❌ MISMATCH');
      console.log('PHP has:', phpIds.join(', '));
      console.log('NestJS has:', nestIds.join(', '));
    }
    
    await prisma.$disconnect();
  } catch (error) {
    console.error('Error:', error);
    await prisma.$disconnect();
  }
}

compareFinal();
