import { PrismaClient } from '@prisma/client';

const prisma = new PrismaClient();

async function checkHotels() {
  const cityCodes = ['126117', '139605', '127067', '133179'];
  const cityNames = ['Mahabalipuram', 'Thanjavur', 'Madurai', 'Rameswaram'];
  
  console.log('🔍 Checking tbo_hotel_master table for these cities...\n');
  
  for (let i = 0; i < cityCodes.length; i++) {
    const cityCode = cityCodes[i];
    const cityName = cityNames[i];
    
    const hotels = await prisma.tbo_hotel_master.findMany({
      where: {
        tbo_city_code: cityCode,
        status: 1,
      },
      take: 5,
    });
    
    console.log(`\n${cityName} (City Code: ${cityCode}):`);
    if (hotels.length === 0) {
      console.log(`   ❌ NO HOTELS IN tbo_hotel_master (status=1)`);
      
      // Check if there are any hotels with different status
      const allStatus = await prisma.tbo_hotel_master.findMany({
        where: { tbo_city_code: cityCode },
        take: 3,
      });
      
      if (allStatus.length > 0) {
        console.log(`   ⚠️  But found ${allStatus.length} hotels with other statuses:`);
        allStatus.forEach((h: any) => {
          console.log(`      - ${h.tbo_hotel_code} (status: ${h.status})`);
        });
      } else {
        console.log(`   🚨 CRITICAL: Zero hotels in tbo_hotel_master for this city!`);
      }
    } else {
      console.log(`   ✅ Found ${hotels.length} active hotels:`);
      hotels.forEach((h: any) => {
        console.log(`      - ${h.tbo_hotel_code} (${h.tbo_hotel_name})`);
      });
    }
  }
  
  // Get total count of tbo_hotel_master records
  const totalCount = await prisma.tbo_hotel_master.count();
  console.log(`\n📊 Total records in tbo_hotel_master: ${totalCount}`);
  
  // Get unique cities
  const uniqueCities = await prisma.tbo_hotel_master.findMany({
    distinct: ['tbo_city_code'],
    select: { tbo_city_code: true },
    take: 10,
  });
  
  console.log(`\n📍 Sample of ${uniqueCities.length} cities with hotels:`);
  for (const city of uniqueCities.slice(0, 5)) {
    const count = await prisma.tbo_hotel_master.count({
      where: { tbo_city_code: city.tbo_city_code },
    });
    console.log(`   - City ${city.tbo_city_code}: ${count} hotels`);
  }
  
  await prisma.$disconnect();
}

checkHotels().catch(err => {
  console.error('Error:', err);
  process.exit(1);
});
