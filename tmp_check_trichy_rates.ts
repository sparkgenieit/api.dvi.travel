
import { PrismaClient } from '@prisma/client';
const prisma = new PrismaClient();

async function main() {
  try {
    const route = await (prisma as any).dvi_itinerary_route_details.findFirst({
      where: { 
        itinerary_plan_ID: 33971,
        next_visiting_location: 'Trichy'
      }
    });

    if (!route) {
      console.log('Route not found');
      return;
    }

    const routeDate = new Date(route.itinerary_route_date);
    console.log(`Route Date: ${routeDate.toISOString()}`);

    const trichyHotelIds = [347, 365, 690, 744];
    
    for (const id of trichyHotelIds) {
      const dc = `day_${routeDate.getDate()}`;
      const y = String(routeDate.getFullYear());
      const m = routeDate.toLocaleString("en-US", { month: "long" });

      const rates = await (prisma as any).dvi_hotel_room_price_book.findMany({
        where: {
          hotel_id: id,
          year: y,
          month: m,
          [dc]: { gt: 0 }
        }
      });

      console.log(`Hotel ID ${id} has ${rates.length} valid rates for ${m} ${routeDate.getDate()}, ${y}`);
    }

  } catch (error) {
    console.error(error);
  } finally {
    await prisma.$disconnect();
  }
}

main();
