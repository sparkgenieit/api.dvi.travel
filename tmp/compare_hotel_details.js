const { PrismaClient } = require('@prisma/client');
const prisma = new PrismaClient();

async function compareHotelDetails() {
  console.log('\n=== PLAN 2 (PHP) HOTEL DETAILS ===\n');
  
  const plan2Details = await prisma.dvi_itinerary_plan_hotel_details.findMany({
    where: { itinerary_plan_id: 2 },
    orderBy: { itinerary_route_id: 'asc' },
    select: {
      itinerary_route_id: true,
      group_type: true,
      hotel_id: true,
      hotel_margin_percentage: true,
      hotel_margin_gst_type: true,
      hotel_margin_gst_percentage: true,
      hotel_margin_rate: true,
      hotel_margin_rate_tax_amt: true,
      hotel_breakfast_cost: true,
      total_room_cost: true,
      total_hotel_cost: true
    }
  });
  
  console.log('Total rows:', plan2Details.length);
  console.log('\nSample data:');
  console.log(JSON.stringify(plan2Details.slice(0, 5), null, 2));
  
  console.log('\n=== PLAN 5 (NestJS) HOTEL DETAILS ===\n');
  
  const plan5Details = await prisma.dvi_itinerary_plan_hotel_details.findMany({
    where: { itinerary_plan_id: 5 },
    orderBy: { itinerary_route_id: 'asc' },
    select: {
      itinerary_route_id: true,
      group_type: true,
      hotel_id: true,
      hotel_margin_percentage: true,
      hotel_margin_gst_type: true,
      hotel_margin_gst_percentage: true,
      hotel_margin_rate: true,
      hotel_margin_rate_tax_amt: true,
      hotel_breakfast_cost: true,
      total_room_cost: true,
      total_hotel_cost: true
    }
  });
  
  console.log('Total rows:', plan5Details.length);
  console.log('\nSample data:');
  console.log(JSON.stringify(plan5Details.slice(0, 5), null, 2));
  
  console.log('\n=== FIELD COMPARISON ===\n');
  if (plan2Details.length > 0 && plan5Details.length > 0) {
    const p2 = plan2Details[0];
    const p5 = plan5Details[0];
    
    console.log('Plan 2 first row hotel_margin_rate:', p2.hotel_margin_rate);
    console.log('Plan 5 first row hotel_margin_rate:', p5.hotel_margin_rate);
    console.log('');
    console.log('Plan 2 first row hotel_margin_rate_tax_amt:', p2.hotel_margin_rate_tax_amt);
    console.log('Plan 5 first row hotel_margin_rate_tax_amt:', p5.hotel_margin_rate_tax_amt);
  }
  
  await prisma.$disconnect();
}

compareHotelDetails().catch(console.error);
