
import { PrismaClient } from '@prisma/client';
const prisma = new PrismaClient();

async function main() {
  try {
    const trichyHotelIds = [347, 365, 690, 744];
    
    for (const id of trichyHotelIds) {
      const rates = await (prisma as any).dvi_hotel_room_price_book.findMany({
        where: {
          hotel_id: id,
          year: { in: ['2025', '2026'] }
        },
        take: 1
      });

      if (rates.length > 0) {
        console.log(`Hotel ID ${id} has rates for year ${rates[0].year} month ${rates[0].month}`);
      } else {
        console.log(`Hotel ID ${id} has NO rates for 2025 or 2026`);
      }
    }

  } catch (error) {
    console.error(error);
  } finally {
    await prisma.$disconnect();
  }
}

main();
