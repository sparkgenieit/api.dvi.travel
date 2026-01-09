import { PrismaClient } from '@prisma/client';

const prisma = new PrismaClient();

async function checkSchema() {
  console.log('\n🔍 CHECKING dvi_itinerary_plan_hotel_details TABLE STRUCTURE\n');
  console.log('════════════════════════════════════════════\n');

  const hotelDetails = await prisma.dvi_itinerary_plan_hotel_details.findMany({
    where: { itinerary_plan_id: 3, deleted: 0 },
    take: 1
  });

  if (hotelDetails.length > 0) {
    const record = hotelDetails[0] as any;
    console.log('Available fields in dvi_itinerary_plan_hotel_details:');
    console.log(JSON.stringify(record, null, 2));
    
    console.log('\n\nField names:');
    console.log(Object.keys(record).join(', '));
  }

  await prisma.$disconnect();
}

checkSchema();
