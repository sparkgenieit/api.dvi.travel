import { PrismaClient } from '@prisma/client';

const prisma = new PrismaClient();

async function verifyHotelNames() {
  const hotelCodes = ['1687511', '5115360', '1839111', '1825083', '1138045'];
  
  console.log('🔍 Checking actual hotel names in tbo_hotel_master...\n');
  
  for (const code of hotelCodes) {
    const hotel = await prisma.tbo_hotel_master.findFirst({
      where: {
        tbo_hotel_code: code,
        status: 1,
      },
    });
    
    if (hotel) {
      console.log(`Hotel Code: ${code}`);
      console.log(`  hotel_name: ${hotel.hotel_name}`);
      console.log(`  city_code: ${hotel.tbo_city_code}`);
      console.log(`  star_rating: ${hotel.star_rating}`);
      console.log(`  city_name: ${hotel.city_name}\n`);
    } else {
      console.log(`Hotel Code: ${code} - NOT FOUND\n`);
    }
  }
  
  await prisma.$disconnect();
}

verifyHotelNames().catch(console.error);
