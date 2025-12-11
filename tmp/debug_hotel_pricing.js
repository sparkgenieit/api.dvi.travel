const { PrismaClient } = require('@prisma/client');
const prisma = new PrismaClient();

async function debugHotelPricing() {
  // Check hotels selected for Plan 5
  console.log('=== HOTELS SELECTED IN PLAN 5 ===\n');
  const hotels = await prisma.dvi_itinerary_plan_hotel_details.findMany({
    where: { itinerary_plan_id: 5 },
    select: {
      hotel_id: true,
      hotel_category_id: true,
      itinerary_route_location: true,
    },
    distinct: ['hotel_id'],
  });
  
  console.log(JSON.stringify(hotels, null, 2));

  // For each hotel, check if rooms exist
  console.log('\n=== CHECKING ROOM AVAILABILITY ===\n');
  
  for (const h of hotels.slice(0, 5)) {
    const rooms = await prisma.dvi_hotel_room_price_book.findMany({
      where: {
        hotel_id: h.hotel_id,
        status: 1,
      },
      select: {
        hotel_price_book_id: true,
        room_id: true,
        room_type_id: true,
        price_type: true,
      },
      take: 5,
    });
    
    console.log(`Hotel ${h.hotel_id} (category ${h.hotel_category_id}, location ${h.itinerary_route_location}):`);
    console.log(`  Available rooms: ${rooms.length}`);
    if (rooms.length > 0) {
      console.log(`  Sample: ${JSON.stringify(rooms[0])}`);
    }
    console.log('');
  }

  await prisma.$disconnect();
}

debugHotelPricing().catch(console.error);
