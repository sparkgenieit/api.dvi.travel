const { PrismaClient } = require('@prisma/client');
const prisma = new PrismaClient();

async function checkItemTypes() {
  try {
    const items = await prisma.$queryRaw`
      SELECT 
        hotspot_order,
        item_type,
        hotspot_ID,
        DATE_FORMAT(hotspot_start_time, '%H:%i') as start_time,
        DATE_FORMAT(hotspot_end_time, '%H:%i') as end_time
      FROM dvi_itinerary_route_hotspot_details
      WHERE itinerary_plan_ID = 13
        AND itinerary_route_ID = 1259
        AND deleted = 0
      ORDER BY hotspot_order
    `;

    console.log('\n=== Day 1 Route 1259 Items ===\n');
    items.forEach(item => {
      const typeLabel = item.item_type === 1 ? 'SIGHTSEEING' : 
                       item.item_type === 2 ? 'BREAK' : 
                       item.item_type === 3 ? 'TRAVEL' : 
                       `UNKNOWN(${item.item_type})`;
      console.log(`Order ${item.hotspot_order}: ${typeLabel} - Hotspot ${item.hotspot_ID} (${item.start_time} - ${item.end_time})`);
    });

    await prisma.$disconnect();
  } catch (error) {
    console.error('Error:', error);
    await prisma.$disconnect();
  }
}

checkItemTypes();
