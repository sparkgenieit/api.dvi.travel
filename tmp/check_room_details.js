const { PrismaClient } = require('@prisma/client');
const prisma = new PrismaClient();

(async () => {
  const rooms = await prisma.dvi_itinerary_plan_hotel_room_details.findMany({
    where: { itinerary_plan_id: 5 },
    take: 5,
    select: {
      hotel_id: true,
      room_id: true,
      room_rate: true,
      total_room_cost: true,
      total_breafast_cost: true
    }
  });
  
  console.log('Plan 5 Room Details:');
  console.log(JSON.stringify(rooms, null, 2));
  
  await prisma.$disconnect();
})();
