import { PrismaClient } from '@prisma/client';

const prisma = new PrismaClient();

async function checkHotelNames() {
  const hotelCode = '1687511';
  
  console.log(`🔍 Checking raw data for hotel ${hotelCode}...\n`);
  
  // Get raw record
  const hotel: any = await prisma.tbo_hotel_master.findFirst({
    where: {
      tbo_hotel_code: hotelCode,
    },
  });
  
  if (hotel) {
    console.log('Full hotel record:');
    console.log(JSON.stringify(hotel, null, 2));
    
    console.log('\n\nField-by-field analysis:');
    console.log(`  id: ${hotel.id}`);
    console.log(`  tbo_hotel_code: ${hotel.tbo_hotel_code}`);
    console.log(`  tbo_city_code: ${hotel.tbo_city_code}`);
    console.log(`  hotel_name: ${hotel.hotel_name}`);
    console.log(`  hotel_address: ${hotel.hotel_address}`);
    console.log(`  city_name: ${hotel.city_name}`);
    console.log(`  star_rating: ${hotel.star_rating}`);
    console.log(`  status: ${hotel.status}`);
  } else {
    console.log('Hotel not found');
  }
  
  await prisma.$disconnect();
}

checkHotelNames().catch(console.error);
