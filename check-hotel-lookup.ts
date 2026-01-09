import { PrismaClient } from '@prisma/client';

const prisma = new PrismaClient();

async function checkHotelLookup() {
  console.log('\n🏨 CHECKING HOTEL LOOKUP\n');
  console.log('════════════════════════════════════════════\n');

  // Get the hotel_id from our plan
  const hotelDetails = await prisma.dvi_itinerary_plan_hotel_details.findMany({
    where: { itinerary_plan_id: 3, deleted: 0 },
    select: { hotel_id: true },
    distinct: ['hotel_id']
  });

  console.log(`Unique hotel IDs in dvi_itinerary_plan_hotel_details for plan 3:`);
  const hotelIds = hotelDetails.map((h: any) => h.hotel_id);
  console.log(hotelIds.join(', '));
  console.log();

  // Look them up in dvi_hotel
  for (const hotelId of hotelIds) {
    const hotel = await prisma.dvi_hotel.findFirst({
      where: { hotel_id: hotelId, deleted: false }
    });

    if (hotel) {
      console.log(`✅ Hotel ID ${hotelId}: FOUND`);
      console.log(`   Name: ${(hotel as any).hotel_name}`);
      console.log(`   Category: ${(hotel as any).hotel_category_id}`);
    } else {
      console.log(`❌ Hotel ID ${hotelId}: NOT FOUND in dvi_hotel table`);
    }
  }

  console.log(`\n════════════════════════════════════════════\n`);

  await prisma.$disconnect();
}

checkHotelLookup();
