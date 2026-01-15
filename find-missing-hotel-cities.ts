import { PrismaClient } from '@prisma/client';

const prisma = new PrismaClient();

async function findMissingHotelCities() {
  console.log('🔍 Finding cities with hotels in tbo_hotel_master but NOT in dvi_hotel...\n');
  
  // Get all unique city codes from tbo_hotel_master
  const masterCities = await prisma.$queryRaw<Array<{tbo_city_code: string, count: number}>>`
    SELECT tbo_city_code, COUNT(*) as count
    FROM tbo_hotel_master
    WHERE status = 1
    GROUP BY tbo_city_code
    ORDER BY count DESC
  `;
  
  console.log(`📊 Found ${masterCities.length} cities in tbo_hotel_master\n`);
  
  const citiesWithMissingHotels: Array<{
    cityCode: string;
    cityName: string;
    inMaster: number;
    inDvi: number;
    missing: number;
  }> = [];
  
  for (const city of masterCities) {
    const cityCode = city.tbo_city_code;
    const masterCount = Number(city.count);
    
    // Count hotels in dvi_hotel for this city
    const dviCount = await prisma.dvi_hotel.count({
      where: {
        tbo_city_code: cityCode,
        deleted: false,
      },
    });
    
    // Get city name
    const cityInfo = await prisma.dvi_cities.findFirst({
      where: {
        tbo_city_code: cityCode,
      },
      select: {
        name: true,
      },
    });
    
    const cityName = cityInfo?.name || 'Unknown';
    const missing = masterCount - dviCount;
    
    if (missing > 0) {
      citiesWithMissingHotels.push({
        cityCode,
        cityName,
        inMaster: masterCount,
        inDvi: dviCount,
        missing,
      });
    }
  }
  
  console.log(`\n🎯 Found ${citiesWithMissingHotels.length} cities with missing hotels in dvi_hotel:\n`);
  console.log('═══════════════════════════════════════════════════════════════');
  console.log('City Name                  | City Code | Master | dvi_hotel | Missing');
  console.log('═══════════════════════════════════════════════════════════════');
  
  citiesWithMissingHotels
    .sort((a, b) => b.missing - a.missing)
    .forEach(city => {
      const nameCol = city.cityName.padEnd(25);
      const codeCol = city.cityCode.padEnd(10);
      const masterCol = String(city.inMaster).padStart(6);
      const dviCol = String(city.inDvi).padStart(9);
      const missingCol = String(city.missing).padStart(7);
      console.log(`${nameCol} | ${codeCol} | ${masterCol} | ${dviCol} | ${missingCol}`);
    });
  
  console.log('═══════════════════════════════════════════════════════════════\n');
  
  // Show completely missing cities (0 in dvi_hotel)
  const completelyMissing = citiesWithMissingHotels.filter(c => c.inDvi === 0);
  console.log(`\n🚨 ${completelyMissing.length} cities with ZERO hotels in dvi_hotel:\n`);
  
  completelyMissing.slice(0, 20).forEach(city => {
    console.log(`   ${city.cityName} (${city.cityCode}): ${city.inMaster} hotels only in tbo_hotel_master`);
  });
  
  if (completelyMissing.length > 20) {
    console.log(`   ... and ${completelyMissing.length - 20} more cities`);
  }
  
  // Show Rameshwaram specifically
  const rameshwaram = citiesWithMissingHotels.find(c => 
    c.cityName.toLowerCase().includes('rameswaram') || 
    c.cityName.toLowerCase().includes('rameshwaram')
  );
  
  if (rameshwaram) {
    console.log(`\n📍 Rameshwaram Status:`);
    console.log(`   City Code: ${rameshwaram.cityCode}`);
    console.log(`   Hotels in tbo_hotel_master: ${rameshwaram.inMaster}`);
    console.log(`   Hotels in dvi_hotel: ${rameshwaram.inDvi}`);
    console.log(`   Missing: ${rameshwaram.missing}`);
  }
  
  await prisma.$disconnect();
}

findMissingHotelCities().catch(console.error);
