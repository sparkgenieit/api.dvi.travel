import { PrismaClient } from '@prisma/client';

const prisma = new PrismaClient();

async function checkDetailedHotelData() {
  console.log('\n🔍 DETAILED HOTEL DATA CHECK\n');
  console.log('════════════════════════════════════════════\n');

  const planId = 3;

  const hotelDetails = await prisma.dvi_itinerary_plan_hotel_details.findMany({
    where: { itinerary_plan_id: planId, deleted: 0 },
    take: 1
  });

  if (hotelDetails.length > 0) {
    const h = hotelDetails[0] as any;
    console.log('All fields of first hotel record:');
    console.log(JSON.stringify(h, null, 2));
  }

  // Also check the actual table structure
  console.log('\n\nChecking all unique hotels for this plan:\n');
  const distinctHotels = await prisma.dvi_itinerary_plan_hotel_details.findMany({
    where: { itinerary_plan_id: planId, deleted: 0 },
    select: {
      hotel_id: true,
      hotel_name: true,
      group_type: true,
      itinerary_route_id: true,
      destination: true
    },
    distinct: ['hotel_id', 'group_type', 'itinerary_route_id']
  });

  console.log(`Found ${distinctHotels.length} distinct hotel combinations:\n`);
  distinctHotels.forEach((h: any, idx) => {
    console.log(`${idx + 1}. Hotel ID: ${h.hotel_id}, Name: "${h.hotel_name}", Group: ${h.group_type}, Route: ${h.itinerary_route_id}, Destination: "${h.destination}"`);
  });

  await prisma.$disconnect();
}

checkDetailedHotelData();
