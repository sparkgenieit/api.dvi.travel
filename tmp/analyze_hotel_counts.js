const { PrismaClient } = require('@prisma/client');
const prisma = new PrismaClient();

async function analyzeHotelCounts() {
  console.log('=== PLAN 2 HOTEL DETAILS ===\n');
  const p2Hotels = await prisma.dvi_itinerary_plan_hotel_details.findMany({
    where: { itinerary_plan_id: 2 },
    select: {
      itinerary_route_id: true,
      group_type: true,
      hotel_id: true,
    },
    orderBy: [
      { itinerary_route_id: 'asc' },
      { group_type: 'asc' }
    ]
  });

  const p2ByRoute = {};
  p2Hotels.forEach(h => {
    const key = h.itinerary_route_id;
    if (!p2ByRoute[key]) p2ByRoute[key] = [];
    p2ByRoute[key].push(h);
  });

  Object.entries(p2ByRoute).forEach(([routeId, hotels]) => {
    console.log(`Route ${routeId}:`);
    hotels.forEach(h => {
      console.log(`  Group ${h.group_type}: Hotel ${h.hotel_id}`);
    });
  });

  console.log('\n=== PLAN 5 HOTEL DETAILS ===\n');
  const p5Hotels = await prisma.dvi_itinerary_plan_hotel_details.findMany({
    where: { itinerary_plan_id: 5 },
    select: {
      itinerary_route_id: true,
      group_type: true,
      hotel_id: true,
    },
    orderBy: [
      { itinerary_route_id: 'asc' },
      { group_type: 'asc' }
    ]
  });

  const p5ByRoute = {};
  p5Hotels.forEach(h => {
    const key = h.itinerary_route_id;
    if (!p5ByRoute[key]) p5ByRoute[key] = [];
    p5ByRoute[key].push(h);
  });

  Object.entries(p5ByRoute).forEach(([routeId, hotels]) => {
    console.log(`Route ${routeId}:`);
    hotels.forEach(h => {
      console.log(`  Group ${h.group_type}: Hotel ${h.hotel_id}`);
    });
  });

  console.log('\n=== ANALYSIS ===');
  console.log(`Plan 2: ${p2Hotels.length} total hotels`);
  console.log(`  Route breakdown: ${Object.values(p2ByRoute).map(h => h.length).join(', ')}`);
  console.log(`Plan 5: ${p5Hotels.length} total hotels`);
  console.log(`  Route breakdown: ${Object.values(p5ByRoute).map(h => h.length).join(', ')}`);

  // Check if Plan 2 is missing some group_types
  const p2Groups = [...new Set(p2Hotels.map(h => h.group_type))].sort();
  const p5Groups = [...new Set(p5Hotels.map(h => h.group_type))].sort();
  console.log(`\nPlan 2 group_types: ${p2Groups.join(', ')}`);
  console.log(`Plan 5 group_types: ${p5Groups.join(', ')}`);

  await prisma.$disconnect();
}

analyzeHotelCounts().catch(console.error);
