const { PrismaClient } = require('@prisma/client');
const prisma = new PrismaClient();

async function main() {
  // NestJS Route 2 (425)
  const nestRoute = await prisma.dvi_itinerary_route_details.findFirst({
    where: { itinerary_plan_ID: 5, deleted: 0, status: 1 },
    orderBy: { itinerary_route_ID: 'asc' },
    skip: 1, // Skip Route 1, get Route 2
  });

  console.log('\n=== NestJS Route 2 (425) ===');
  console.log(`Start: ${nestRoute.route_start_time}, End: ${nestRoute.route_end_time}`);
  
  const items = await prisma.dvi_itinerary_route_hotspot_details.findMany({
    where: {
      itinerary_route_ID: nestRoute.itinerary_route_ID,
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
    },
  });

  console.log('\nTimeline:');
  items.forEach(item => {
    const type = item.item_type === 3 ? 'TRAVEL' : 
                 item.item_type === 4 ? 'VISIT' : 
                 item.item_type === 1 ? 'BREAK' : `TYPE${item.item_type}`;
    console.log(`  ${item.hotspot_order}. ${type.padEnd(7)} h${item.hotspot_ID || '?'}\t${item.hotspot_start_time} - ${item.hotspot_end_time}\t(travel: ${item.hotspot_traveling_time || 'N/A'})`);
  });

  // PHP Route 2 (179)
  console.log('\n=== PHP Route 2 (179) ===');
  const phpRoute = await prisma.dvi_itinerary_route_details.findUnique({
    where: { itinerary_route_ID: 179 },
  });
  console.log(`Start: ${phpRoute.route_start_time}, End: ${phpRoute.route_end_time}`);

  const phpItems = await prisma.dvi_itinerary_route_hotspot_details.findMany({
    where: {
      itinerary_route_ID: 179,
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
    },
  });

  console.log('\nTimeline:');
  phpItems.forEach(item => {
    const type = item.item_type === 3 ? 'TRAVEL' : 
                 item.item_type === 4 ? 'VISIT' : 
                 item.item_type === 1 ? 'BREAK' : `TYPE${item.item_type}`;
    console.log(`  ${item.hotspot_order}. ${type.padEnd(7)} h${item.hotspot_ID || '?'}\t${item.hotspot_start_time} - ${item.hotspot_end_time}\t(travel: ${item.hotspot_traveling_time || 'N/A'})`);
  });
}

main()
  .catch(console.error)
  .finally(() => prisma.$disconnect());
