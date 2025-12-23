
import { PrismaClient } from '@prisma/client';
const prisma = new PrismaClient();

async function main() {
  try {
    // 1. Check Hotel 268 (Vellore hotel that was picked)
    const hotel268 = await (prisma as any).dvi_hotel.findFirst({
      where: { hotel_id: 268 }
    });
    console.log('Hotel 268 (Picked):', {
      name: hotel268?.hotel_name,
      cityId: hotel268?.hotel_city,
      category: hotel268?.hotel_category
    });

    // 2. Check Trichy City ID
    const trichyCity = await (prisma as any).dvi_cities.findFirst({
      where: { name: 'Thiruchirapalli' }
    });
    console.log('Trichy City (Thiruchirapalli):', trichyCity);

    if (trichyCity) {
      // 3. Check hotels in Trichy
      const trichyHotels = await (prisma as any).dvi_hotel.findMany({
        where: { 
          hotel_city: String(trichyCity.id),
          deleted: false,
          status: 1
        }
      });
      console.log(`Found ${trichyHotels.length} active hotels in Trichy (ID ${trichyCity.id})`);
      
      for (const h of trichyHotels) {
        console.log(`  - ${h.hotel_name} (ID: ${h.hotel_id}, Category: ${h.hotel_category})`);
      }
    }

  } catch (error) {
    console.error(error);
  } finally {
    await prisma.$disconnect();
  }
}

main();
