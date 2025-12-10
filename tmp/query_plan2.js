const { PrismaClient } = require('@prisma/client');
const prisma = new PrismaClient();

async function queryPlan2() {
  // Get routes for Plan 2
  const routes = await prisma.dvi_itinerary_route_details.findMany({
    where: { 
      itinerary_plan_ID: 2,
      deleted: 0,
      status: 1
    },
    orderBy: { itinerary_route_ID: 'asc' },
    select: {
      itinerary_route_ID: true,
      location_name: true,
      next_visiting_location: true,
      route_start_time: true,
      route_end_time: true,
      direct_to_next_visiting_place: true
    }
  });

  console.log('\n=== PLAN 2 ROUTES ===');
  console.table(routes);

  // Get hotspot details for Plan 2
  const hotspots = await prisma.dvi_itinerary_route_hotspot_details.findMany({
    where: {
      itinerary_plan_ID: 2,
      deleted: 0,
      status: 1,
      item_type: { in: [3, 4] }
    },
    orderBy: [
      { itinerary_route_ID: 'asc' },
      { hotspot_order: 'asc' }
    ],
    select: {
      itinerary_route_ID: true,
      item_type: true,
      hotspot_ID: true,
      hotspot_order: true
    }
  });

  console.log('\n=== PLAN 2 HOTSPOTS (item_type 3 & 4) ===');
  console.table(hotspots);

  // Get hotspot names
  const hotspotIds = [...new Set(hotspots.map(h => h.hotspot_ID).filter(id => id > 0))];
  const hotspotDetails = await prisma.dvi_hotspot_place.findMany({
    where: {
      hotspot_ID: { in: hotspotIds }
    },
    select: {
      hotspot_ID: true,
      hotspot_name: true,
      hotspot_priority: true
    }
  });

  console.log('\n=== HOTSPOT DETAILS ===');
  console.table(hotspotDetails);

  await prisma.$disconnect();
}

queryPlan2().catch(e => {
  console.error(e);
  process.exit(1);
});
