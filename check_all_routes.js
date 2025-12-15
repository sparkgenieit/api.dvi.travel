const { PrismaClient } = require('@prisma/client');
const prisma = new PrismaClient();

async function checkAllRoutes() {
  try {
    const routes = await prisma.dvi_itinerary_route_details.findMany({
      where: { itinerary_plan_ID: 13, deleted: 0 },
      orderBy: { itinerary_route_ID: 'asc' },
    });

    console.log(`\n=== All Routes for Plan 13 ===\n`);
    
    for (const route of routes) {
      const items = await prisma.$queryRaw`
        SELECT 
          hotspot_order,
          item_type,
          hotspot_ID,
          DATE_FORMAT(hotspot_start_time, '%H:%i') as start_time,
          DATE_FORMAT(hotspot_end_time, '%H:%i') as end_time
        FROM dvi_itinerary_route_hotspot_details
        WHERE itinerary_plan_ID = 13
          AND itinerary_route_ID = ${route.itinerary_route_ID}
          AND deleted = 0
        ORDER BY hotspot_order
      `;

      console.log(`Route ${route.itinerary_route_ID}:`);
      items.forEach(item => {
        const typeLabel = item.item_type === 1 ? 'SIGHT' : 
                         item.item_type === 2 ? 'BREAK' : 
                         item.item_type === 3 ? 'TRAVEL' : 
                         item.item_type === 4 ? 'SIGHT' : 
                         `TYPE${item.item_type}`;
        console.log(`  ${item.hotspot_order}. ${typeLabel} #${item.hotspot_ID} (${item.start_time}-${item.end_time})`);
      });
      console.log('');
    }

    await prisma.$disconnect();
  } catch (error) {
    console.error('Error:', error);
    await prisma.$disconnect();
  }
}

checkAllRoutes();
