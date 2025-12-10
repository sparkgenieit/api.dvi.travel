const { PrismaClient } = require('@prisma/client');

const prisma = new PrismaClient();

async function main() {
  try {
    // Get total count for plan 4
    const totalPlan4 = await prisma.$queryRaw`
      SELECT COUNT(*) as total
      FROM dvi_itinerary_route_hotspot_details
      WHERE itinerary_plan_ID = 4
    `;

    console.log('Plan 4 Total Rows:', totalPlan4[0].total);

    // Get breakdown by route
    const byRoute = await prisma.$queryRaw`
      SELECT 
        itinerary_route_ID,
        COUNT(*) as total_items,
        SUM(CASE WHEN item_type = 1 THEN 1 ELSE 0 END) as travel_count,
        SUM(CASE WHEN item_type IN (3, 4) THEN 1 ELSE 0 END) as hotspot_count
      FROM dvi_itinerary_route_hotspot_details
      WHERE itinerary_plan_ID = 4
      GROUP BY itinerary_route_ID
      ORDER BY itinerary_route_ID
    `;

    console.log('\nBreakdown by Route:');
    console.log('─'.repeat(80));
    byRoute.forEach(r => {
      console.log(`Route ${r.itinerary_route_ID}: Total=${r.total_items}, Travel=${r.travel_count}, Hotspots=${r.hotspot_count}`);
    });

    // Get item type breakdown
    const byType = await prisma.$queryRaw`
      SELECT 
        item_type,
        COUNT(*) as count
      FROM dvi_itinerary_route_hotspot_details
      WHERE itinerary_plan_ID = 4
      GROUP BY item_type
    `;

    console.log('\nBreakdown by Item Type:');
    console.log('─'.repeat(80));
    byType.forEach(t => {
      const typeLabel = t.item_type === 1 ? 'Travel' : t.item_type === 3 ? 'Travel' : 'Hotspot';
      console.log(`Type ${t.item_type} (${typeLabel}): ${t.count}`);
    });

  } catch (err) {
    console.error('Error:', err.message);
  } finally {
    await prisma.$disconnect();
  }
}

main();
