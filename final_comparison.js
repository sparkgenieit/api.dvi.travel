const { PrismaClient } = require('@prisma/client');
const prisma = new PrismaClient();

async function finalComparison() {
  // PHP Route 178
  const phpHotspots = await prisma.dvi_itinerary_route_hotspot_details.findMany({
    where: {
      itinerary_plan_ID: 2,
      itinerary_route_ID: 178,
      deleted: 0,
      status: 1,
      item_type: { in: [3, 4] }
    }
  });
  
  const phpIds = [...new Set(phpHotspots.map(h => h.hotspot_ID))].sort();
  
  // NestJS Route 232
  const nestHotspots = await prisma.dvi_itinerary_route_hotspot_details.findMany({
    where: {
      itinerary_plan_ID: 5,
      itinerary_route_ID: 232,
      deleted: 0,
      status: 1,
      item_type: { in: [3, 4] }
    }
  });
  
  const nestIds = [...new Set(nestHotspots.map(h => h.hotspot_ID))].sort();
  
  console.log('=== FINAL COMPARISON ===\n');
  console.log('PHP Route 178 hotspots:', phpIds.join(', '));
  console.log('NestJS Route 232 hotspots:', nestIds.join(', '));
  console.log('');
  
  if (JSON.stringify(phpIds) === JSON.stringify(nestIds)) {
    console.log('✅✅✅ PERFECT MATCH! Both routes have identical hotspots! ✅✅✅');
  } else {
    console.log('❌ MISMATCH');
  }
  
  await prisma.$disconnect();
}

finalComparison();
