import { PrismaClient } from '@prisma/client';

const prisma = new PrismaClient();

async function checkHotels() {
  const hotels = await prisma.tbo_hotel_master.findMany({
    where: { status: 1 },
    take: 30,
    select: { tbo_hotel_code: true, tbo_city_code: true, hotel_name: true }
  });
  
  console.log('Sample Hotels in tbo_hotel_master:');
  hotels.forEach(h => {
    console.log(`  ${h.tbo_city_code}: ${h.tbo_hotel_code} - ${h.hotel_name}`);
  });
  
  await prisma.$disconnect();
}

checkHotels();
