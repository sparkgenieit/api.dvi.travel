
import { PrismaClient } from '@prisma/client';
const prisma = new PrismaClient();

async function main() {
  try {
    // Search for the quote ID in dvi_itinerary_plan_details
    const plan = await (prisma as any).dvi_itinerary_plan_details.findFirst({
      where: {
        OR: [
          { itinerary_quote_ID: 'DVI20251214' },
          { itinerary_quote_ID: { contains: 'DVI20251214' } }
        ]
      }
    });

    if (!plan) {
      console.log('Plan with Quote ID DVI20251214 not found.');
      return;
    }

    console.log(`Found Plan ID: ${plan.itinerary_plan_ID} for Quote: ${plan.itinerary_quote_ID}`);
    console.log(`Arrival: ${plan.arrival_location} | Departure: ${plan.departure_location}`);

    // Get routes
    const routes = await (prisma as any).dvi_itinerary_route_details.findMany({
      where: { itinerary_plan_ID: plan.itinerary_plan_ID },
      orderBy: { itinerary_route_ID: 'asc' }
    });

    console.log('\n--- Hotel Assignments ---');
    for (const r of routes) {
      // Get hotel details for this route
      const hotelDetails = await (prisma as any).dvi_itinerary_plan_hotel_details.findFirst({
        where: { itinerary_route_id: r.itinerary_route_ID }
      });

      if (hotelDetails) {
        const hotel = await (prisma as any).dvi_hotel.findFirst({
          where: { hotel_id: hotelDetails.hotel_id }
        });
        const city = hotel ? await (prisma as any).dvi_cities.findFirst({ where: { id: Number(hotel.hotel_city) } }) : null;
        
        console.log(`Route: ${r.location_name} -> ${r.next_visiting_location}`);
        console.log(`  Hotel: ${hotel?.hotel_name} (ID: ${hotel?.hotel_id})`);
        console.log(`  City: ${city?.name} (ID: ${hotel?.hotel_city})`);
        console.log('  ---');
      } else {
        console.log(`Route: ${r.location_name} -> ${r.next_visiting_location}`);
        console.log('  Hotel: NONE ASSIGNED');
        console.log('  ---');
      }
    }

  } catch (error) {
    console.error(error);
  } finally {
    await prisma.$disconnect();
  }
}

main();
