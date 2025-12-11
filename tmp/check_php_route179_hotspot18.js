const { PrismaClient } = require('@prisma/client');
const prisma = new PrismaClient();

async function main() {
  // Get PHP Route 179 hotspot 18 details
  const rows = await prisma.dvi_itinerary_route_hotspot_details.findMany({
    where: {
      itinerary_plan_ID: 2,
      itinerary_route_ID: 179,
      hotspot_ID: { in: [4, 18] },
      deleted: 0,
      status: 1,
    },
    orderBy: { hotspot_order: 'asc' },
    select: {
      hotspot_order: true,
      item_type: true,
      hotspot_ID: true,
      hotspot_start_time: true,
      hotspot_end_time: true,
      hotspot_traveling_time: true,
    }
  });

  console.log('\nPHP Route 179 - Hotspots 4 and 18:');
  rows.forEach(r => {
    const type = r.item_type === 3 ? 'TRAVEL' : r.item_type === 4 ? 'VISIT' : r.item_type;
    console.log(`Order ${r.hotspot_order}: ${type} h${r.hotspot_ID} | ${r.hotspot_start_time} -> ${r.hotspot_end_time} | Duration: ${r.hotspot_traveling_time}`);
  });

  // Calculate: If we START at route start time and process hotspot 4, when do we arrive at 18?
  const route = await prisma.dvi_itinerary_route_details.findUnique({
    where: { itinerary_route_ID: 179 },
    select: {
      route_start_time: true,
      route_end_time: true,
    }
  });

  console.log(`\nRoute start: ${route.route_start_time}`);
  console.log(`Route end: ${route.route_end_time}`);

  // Get hotspot 18 operating hours
  const timing = await prisma.dvi_hotspot_timing.findMany({
    where: {
      hotspot_ID: 18,
      hotspot_timing_day: 3, // Thursday
      status: 1,
      deleted: 0,
    },
    select: {
      hotspot_start_time: true,
      hotspot_end_time: true,
    }
  });

  console.log('\nHotspot 18 operating hours (Thursday):');
  timing.forEach(t => {
    const start = t.hotspot_start_time;
    const end = t.hotspot_end_time;
    const startStr = `${String(start.getUTCHours()).padStart(2, '0')}:${String(start.getUTCMinutes()).padStart(2, '0')}`;
    const endStr = `${String(end.getUTCHours()).padStart(2, '0')}:${String(end.getUTCMinutes()).padStart(2, '0')}`;
    console.log(`  ${startStr} - ${endStr}`);
  });
}

main()
  .catch(console.error)
  .finally(() => prisma.$disconnect());
