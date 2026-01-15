import { PrismaClient } from '@prisma/client';

const prisma = new PrismaClient();

async function checkMissingHotels() {
  // Hotel codes from the API response that show generic names
  const missingHotelCodes = [
    '1687511', // Rameswaram - Budget
    '5115360', // Rameswaram - Mid-Range
    '1839111', // Rameswaram - Mid-Range
    '1825083', // Rameswaram - Mid-Range
    '1138045', // Rameswaram - Premium
  ];
  
  console.log('🔍 Checking if these hotels exist in dvi_hotel table...\n');
  
  for (const hotelCode of missingHotelCodes) {
    console.log(`\n📍 Hotel Code: ${hotelCode}`);
    
    // Check dvi_hotel table
    const dviHotel = await prisma.dvi_hotel.findFirst({
      where: {
        tbo_hotel_code: hotelCode,
        deleted: false,
      },
    });
    
    if (dviHotel) {
      console.log(`   ✅ Found in dvi_hotel: ${(dviHotel as any).hotel_name}`);
      console.log(`      City Code: ${(dviHotel as any).tbo_city_code}`);
      console.log(`      Address: ${(dviHotel as any).hotel_address || 'N/A'}`);
    } else {
      console.log(`   ❌ NOT FOUND in dvi_hotel table`);
    }
    
    // Check tbo_hotel_master table
    const tboHotel = await prisma.tbo_hotel_master.findFirst({
      where: {
        tbo_hotel_code: hotelCode,
      },
    });
    
    if (tboHotel) {
      console.log(`   ✅ Found in tbo_hotel_master: ${(tboHotel as any).tbo_hotel_name}`);
      console.log(`      City Code: ${(tboHotel as any).tbo_city_code}`);
      console.log(`      Status: ${(tboHotel as any).status}`);
    } else {
      console.log(`   ❌ NOT FOUND in tbo_hotel_master table`);
    }
  }
  
  // Also check Rameswaram city code
  console.log('\n\n🏙️ Checking Rameswaram city hotels...');
  const rameswaramCityCode = '133179';
  
  const dviHotelsInCity = await prisma.dvi_hotel.count({
    where: {
      tbo_city_code: rameswaramCityCode,
      deleted: false,
    },
  });
  
  const tboHotelsInCity = await prisma.tbo_hotel_master.count({
    where: {
      tbo_city_code: rameswaramCityCode,
      status: 1,
    },
  });
  
  console.log(`\nRameswaram (City Code: ${rameswaramCityCode}):`);
  console.log(`   dvi_hotel: ${dviHotelsInCity} hotels`);
  console.log(`   tbo_hotel_master: ${tboHotelsInCity} active hotels`);
  
  // Sample some hotels from Rameswaram to see what exists
  if (dviHotelsInCity > 0) {
    console.log('\n   Sample hotels in dvi_hotel for Rameswaram:');
    const samples = await prisma.dvi_hotel.findMany({
      where: {
        tbo_city_code: rameswaramCityCode,
        deleted: false,
      },
      take: 5,
    });
    
    samples.forEach((hotel: any) => {
      console.log(`      - ${hotel.tbo_hotel_code}: ${hotel.hotel_name}`);
    });
  }
  
  if (tboHotelsInCity > 0) {
    console.log('\n   Sample hotels in tbo_hotel_master for Rameswaram:');
    const samples = await prisma.tbo_hotel_master.findMany({
      where: {
        tbo_city_code: rameswaramCityCode,
        status: 1,
      },
      take: 5,
    });
    
    samples.forEach((hotel: any) => {
      console.log(`      - ${hotel.tbo_hotel_code}: ${hotel.tbo_hotel_name}`);
    });
  }
  
  await prisma.$disconnect();
}

checkMissingHotels().catch(console.error);
