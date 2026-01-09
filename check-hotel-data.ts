import { PrismaClient } from '@prisma/client';

const prisma = new PrismaClient();

async function checkHotelData() {
  const quoteId = 'DVI2026011';
  
  console.log('\n📊 CHECKING HOTEL DATA IN DATABASE FOR QUOTE: DVI2026011\n');
  console.log('════════════════════════════════════════════\n');

  // 1. Get plan
  const plan = await prisma.dvi_itinerary_plan_details.findFirst({
    where: { itinerary_quote_ID: quoteId, deleted: 0 }
  });

  if (!plan) {
    console.log(`❌ Plan not found for quote ${quoteId}`);
    await prisma.$disconnect();
    return;
  }

  const planId = (plan as any).itinerary_plan_ID;
  console.log(`✅ Plan Found:`);
  console.log(`   - Quote ID: ${quoteId}`);
  console.log(`   - Plan ID: ${planId}\n`);

  // 2. Check dvi_itinerary_plan_hotel_details
  const hotelDetailsCount = await prisma.dvi_itinerary_plan_hotel_details.count({
    where: { itinerary_plan_id: planId, deleted: 0 }
  });

  console.log(`📋 dvi_itinerary_plan_hotel_details table:`);
  console.log(`   - Records for this plan: ${hotelDetailsCount}`);
  
  if (hotelDetailsCount > 0) {
    const samples = await prisma.dvi_itinerary_plan_hotel_details.findMany({
      where: { itinerary_plan_id: planId, deleted: 0 },
      take: 3
    });
    
    samples.forEach((h: any, idx) => {
      console.log(`\n   Sample ${idx + 1}:`);
      console.log(`     - Hotel ID: ${h.hotel_id}`);
      console.log(`     - Hotel Name: ${h.hotel_name}`);
      console.log(`     - Group Type: ${h.group_type}`);
      console.log(`     - Route ID: ${h.itinerary_route_id}`);
    });
  } else {
    console.log(`   ❌ NO HOTEL RECORDS FOUND!`);
  }

  // 3. Check routes
  const routes = await prisma.dvi_itinerary_route_details.findMany({
    where: { itinerary_plan_ID: planId, deleted: 0 },
    take: 5
  });

  console.log(`\n📅 Routes for this plan: ${routes.length}`);
  routes.forEach((r: any, idx) => {
    console.log(`   ${idx + 1}. ${(r as any).location_name} → ${(r as any).next_visiting_location}`);
  });

  // 4. Explanation
  console.log(`\n════════════════════════════════════════════`);
  console.log(`\n📌 ANALYSIS:`);
  
  if (hotelDetailsCount === 0) {
    console.log(`\n❌ ISSUE: dvi_itinerary_plan_hotel_details is EMPTY!`);
    console.log(`\nThis means:\n`);
    console.log(`1. The hotel_details endpoint either:`);
    console.log(`   - Was never called for this quote, OR`);
    console.log(`   - Called but failed to save data to database, OR`);
    console.log(`   - Was called but returned 0 hotels ("No Hotels Available")`);
    console.log(`\n2. Therefore, hotel_room_details has nothing to read from DB`);
    console.log(`\nSOLUTION:`);
    console.log(`- Call GET /api/v1/itineraries/hotel_details/DVI2026011 first`);
    console.log(`- This should generate hotels from TBO API and save to DB`);
    console.log(`- Then hotel_room_details will have data to return`);
  } else {
    console.log(`\n✅ Database has ${hotelDetailsCount} hotel records`);
    console.log(`   hotel_room_details should return this data`);
  }

  console.log(`\n════════════════════════════════════════════\n`);

  await prisma.$disconnect();
}

checkHotelData();
