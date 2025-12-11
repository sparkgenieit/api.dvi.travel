const { PrismaClient } = require('@prisma/client');
const prisma = new PrismaClient();

async function main() {
  // Get Route 425 travel rows
  const travels = await prisma.dvi_itinerary_route_hotspot_details.findMany({
    where: {
      itinerary_route_ID: 425,
      item_type: 3, // Travel
      deleted: 0
    },
    select: {
      route_hotspot_ID: true,
      hotspot_ID: true,
      hotspot_order: true,
      hotspot_travelling_distance: true,
      hotspot_traveling_time: true
    },
    orderBy: { hotspot_order: 'asc' }
  });

  console.log(`\n=== ROUTE 425 TRAVEL ROWS ===\n`);
  travels.forEach(t => {
    const mins = t.hotspot_traveling_time.getUTCHours() * 60 + t.hotspot_traveling_time.getUTCMinutes();
    console.log(`Order ${t.hotspot_order}: To H${t.hotspot_ID} - Distance: ${t.hotspot_travelling_distance} km, Time: ${mins} min (${t.hotspot_traveling_time.toISOString().substr(11, 8)})`);
  });
}

main()
  .catch(console.error)
  .finally(() => prisma.$disconnect());
