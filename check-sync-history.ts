import { PrismaClient } from '@prisma/client';

const prisma = new PrismaClient();

async function checkSyncHistory() {
  console.log('📅 Checking when Rameswaram hotels were last synced...\n');
  
  const rameswaramHotels = await prisma.tbo_hotel_master.findMany({
    where: {
      tbo_city_code: '133179',
      status: 1,
    },
    select: {
      tbo_hotel_code: true,
      hotel_name: true,
      created_at: true,
      updated_at: true,
    },
    take: 10,
  });
  
  console.log(`Total Rameswaram hotels in database: ${rameswaramHotels.length}\n`);
  
  if (rameswaramHotels.length > 0) {
    console.log('Sample hotels with sync dates:\n');
    rameswaramHotels.forEach((hotel: any) => {
      console.log(`${hotel.tbo_hotel_code} - ${hotel.hotel_name}`);
      console.log(`  Created: ${hotel.created_at}`);
      console.log(`  Updated: ${hotel.updated_at}\n`);
    });
  }
  
  // Check other cities
  const cities = [
    { code: '126117', name: 'Mahabalipuram' },
    { code: '139605', name: 'Thanjavur' },
    { code: '127067', name: 'Madurai' },
  ];
  
  console.log('\n📊 Comparing hotel counts across cities:\n');
  for (const city of cities) {
    const count = await prisma.tbo_hotel_master.count({
      where: {
        tbo_city_code: city.code,
        status: 1,
      },
    });
    console.log(`${city.name} (${city.code}): ${count} hotels`);
  }
  
  await prisma.$disconnect();
}

checkSyncHistory().catch(console.error);
