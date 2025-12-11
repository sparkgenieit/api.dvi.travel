const { PrismaClient } = require('@prisma/client');
const prisma = new PrismaClient();

(async () => {
  // Check hotel 522 price books
  const roomPriceCount = await prisma.dvi_hotel_room_price_book.count({ where: { hotel_id: 522 } });
  console.log('Hotel 522 room prices:', roomPriceCount);
  
  const mealPriceCount = await prisma.dvi_hotel_meal_price_book.count({ where: { hotel_id: 522 } });
  console.log('Hotel 522 meal prices:', mealPriceCount);
  
  // Check Plan 2 hotels
  const plan2Hotels = await prisma.dvi_itinerary_plan_hotel_details.findMany({
    where: { itinerary_plan_id: 2 },
    select: { hotel_id: true, hotel_margin_rate: true },
    distinct: ['hotel_id'],
    take: 3
  });
  console.log('\nPlan 2 hotels with margin_rate:', JSON.stringify(plan2Hotels, null, 2));
  
  // Check if Plan 2 hotels have price books
  if (plan2Hotels.length > 0) {
    const plan2HotelId = plan2Hotels[0].hotel_id;
    const plan2RoomPrices = await prisma.dvi_hotel_room_price_book.count({ where: { hotel_id: plan2HotelId } });
    console.log(`\nHotel ${plan2HotelId} (from Plan 2) room prices:`, plan2RoomPrices);
  }
  
  await prisma.$disconnect();
})();
