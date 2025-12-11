const { PrismaClient } = require('@prisma/client');
const prisma = new PrismaClient();

function formatTime(date) {
  if (!date) return 'NULL';
  if (typeof date === 'string') return date;
  const h = String(date.getUTCHours()).padStart(2, '0');
  const m = String(date.getUTCMinutes()).padStart(2, '0');
  const s = String(date.getUTCSeconds()).padStart(2, '0');
  return `${h}:${m}:${s}`;
}

async function checkRouteStartTimes() {
  const routes = await prisma.dvi_itinerary_route_details.findMany({
    where: { itinerary_plan_ID: 2, deleted: 0 },
    orderBy: [{ itinerary_route_date: 'asc' }, { itinerary_route_ID: 'asc' }]
  });
  
  for (const route of routes) {
    console.log(`Route ${route.itinerary_route_ID}: ${route.location_name} → ${route.next_visiting_location}`);
    console.log(`  Start: ${formatTime(route.route_start_time)}`);
    console.log(`  End: ${formatTime(route.route_end_time)}`);
    console.log(`  Date: ${route.itinerary_route_date}\n`);
  }
  
  await prisma.$disconnect();
}

checkRouteStartTimes();
