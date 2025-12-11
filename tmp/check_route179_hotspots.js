const { PrismaClient } = require('@prisma/client');
const prisma = new PrismaClient();

async function main() {
  const hotspots = await prisma.dvi_itinerary_route_hotspot_details.findMany({
    where: {
      itinerary_route_ID: 179,
      deleted: 0
    },
    select: {
      route_hotspot_ID: true,
      hotspot_ID: true,
      item_type: true,
      hotspot_order: true
    },
    orderBy: { hotspot_order: 'asc' }
  });

  console.log(`\nRoute 179 hotspots (${hotspots.length} found):\n`);
  hotspots.forEach(h => {
    const type = h.item_type === 1 ? 'VISIT' : h.item_type === 2 ? 'BREAK' : h.item_type === 3 ? 'TRAVEL' : 'UNKNOWN';
    console.log(`Order ${h.hotspot_order}: H${h.hotspot_ID} (${type}) - route_hotspot_ID: ${h.route_hotspot_ID}`);
  });
}

main()
  .catch(console.error)
  .finally(() => prisma.$disconnect());
