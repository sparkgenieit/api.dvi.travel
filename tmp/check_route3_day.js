const { PrismaClient } = require('@prisma/client');
const prisma = new PrismaClient();

async function checkRoute3Day() {
  const route = await prisma.dvi_itinerary_route_details.findFirst({
    where: { itinerary_plan_ID: 2, deleted: 0 },
    orderBy: { itinerary_route_date: 'asc' },
    skip: 2,  // Third route (index 2)
    take: 1
  });

  console.log('Route 3 Date:', route.itinerary_route_date);
  const date = new Date(route.itinerary_route_date);
  const dayNames = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
  console.log('Day of week:', dayNames[date.getDay()], `(${date.getDay()})`);

  await prisma.$disconnect();
}

checkRoute3Day();
