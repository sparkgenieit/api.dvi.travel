const { PrismaClient } = require('@prisma/client');
const prisma = new PrismaClient();

(async () => {
  // Check room details for route 632 (which has hotel 522 in hotel_details)
  const rooms = await prisma.dvi_itinerary_plan_hotel_room_details.findMany({
    where: { 
      itinerary_plan_id: 5,
      itinerary_route_id: 632
    },
    select: {
      hotel_id: true,
      room_id: true,
      room_rate: true,
      group_type: true
    }
  });
  
  console.log('Plan 5 room_details for route 632:');
  console.log(JSON.stringify(rooms, null, 2));
  
  await prisma.$disconnect();
})();
