const { PrismaClient } = require('@prisma/client');
const prisma = new PrismaClient();

async function checkHotels() {
  console.log('\n=== PLAN 5 HOTEL DETAILS ===\n');
  
  const plan5Hotels = await prisma.dvi_itinerary_plan_hotel_details.findMany({
    where: { itinerary_plan_id: 5 },
    select: {
      hotel_id: true,
      itinerary_route_id: true
    }
  });
  
  console.log(JSON.stringify(plan5Hotels, null, 2));
  
  console.log('\n=== PLAN 2 HOTEL DETAILS ===\n');
  
  const plan2Hotels = await prisma.dvi_itinerary_plan_hotel_details.findMany({
    where: { itinerary_plan_id: 2 },
    select: {
      hotel_id: true,
      itinerary_route_id: true
    }
  });
  
  console.log(JSON.stringify(plan2Hotels, null, 2));
  
  await prisma.$disconnect();
}

checkHotels().catch(console.error);
