/**
 * Test Script: Understand how /api/v1/itineraries/hotel_details/DVI2026011 works
 * 
 * This script shows:
 * 1. What data is queried from the database
 * 2. How the payload is built
 * 3. What TBO API calls are made
 */

const { PrismaClient } = require('@prisma/client');
const prisma = new PrismaClient();

async function analyzeItineraryFlow() {
  const quoteId = 'DVI2026011';
  
  console.log(`\n${'='.repeat(80)}`);
  console.log(`ANALYZING: GET /api/v1/itineraries/hotel_details/${quoteId}`);
  console.log(`${'='.repeat(80)}\n`);

  try {
    // STEP 1: Get itinerary plan
    console.log(`📋 STEP 1: Query dvi_itinerary_plan_details`);
    console.log(`   SQL: SELECT * FROM dvi_itinerary_plan_details WHERE itinerary_quote_ID = '${quoteId}'`);
    
    const plan = await prisma.dvi_itinerary_plan_details.findFirst({
      where: { itinerary_quote_ID: quoteId, deleted: 0 },
    });

    if (!plan) {
      console.log(`   ❌ Quote not found!\n`);
      return;
    }

    console.log(`   ✅ Found Plan:`);
    console.log(`      - Plan ID: ${plan.itinerary_plan_ID}`);
    console.log(`      - No. of Nights: ${plan.no_of_nights}`);
    console.log(`      - Quote ID: ${plan.itinerary_quote_ID}\n`);

    const planId = plan.itinerary_plan_ID;
    const noOfNights = Number(plan.no_of_nights || 0);

    // STEP 2: Get routes (destinations and dates)
    console.log(`📅 STEP 2: Query dvi_itinerary_route_details`);
    console.log(`   SQL: SELECT * FROM dvi_itinerary_route_details WHERE itinerary_plan_ID = ${planId} ORDER BY itinerary_route_date ASC`);
    
    const routes = await prisma.dvi_itinerary_route_details.findMany({
      where: { itinerary_plan_ID: planId, deleted: 0 },
      orderBy: { itinerary_route_date: 'asc' },
    });

    console.log(`   ✅ Found ${routes.length} routes:\n`);
    routes.forEach((route, index) => {
      console.log(`   Route ${index + 1}:`);
      console.log(`      - Route ID: ${route.itinerary_route_ID}`);
      console.log(`      - Date: ${new Date(route.itinerary_route_date).toISOString().split('T')[0]}`);
      console.log(`      - Location (departing from): ${route.location_name}`);
      console.log(`      - Next destination (staying): ${route.next_visiting_location}\n`);
    });

    // STEP 3: For each route, map destination to TBO city code
    console.log(`🗺️  STEP 3: Map destinations to TBO city codes\n`);
    
    for (let i = 0; i < routes.length; i++) {
      const route = routes[i];
      const destination = route.next_visiting_location;
      const routeDate = new Date(route.itinerary_route_date).toISOString().split('T')[0];
      const isLastRoute = i === routes.length - 1;
      const shouldSkip = isLastRoute && i >= noOfNights;

      console.log(`   Route ${i + 1}: "${destination}"`);

      if (shouldSkip) {
        console.log(`      ⏭️  SKIP (Last route - departure day, no hotel needed)\n`);
        continue;
      }

      // Query city mapping
      let city = await prisma.dvi_cities.findFirst({
        where: { name: destination },
      });

      if (!city) {
        const firstPart = destination.split(',')[0].trim();
        if (firstPart !== destination) {
          city = await prisma.dvi_cities.findFirst({
            where: { name: firstPart },
          });
        }
      }

      if (!city) {
        console.log(`      ❌ City NOT FOUND in database\n`);
        continue;
      }

      console.log(`      ✅ Found in dvi_cities:`);
      console.log(`         - City Name: ${city.name}`);
      console.log(`         - TBO City Code: ${city.tbo_city_code}`);
      console.log(`         - City ID: ${city.id}\n`);

      // STEP 4: Show what TBO API call would be made
      const checkOutDate = new Date(routeDate);
      checkOutDate.setDate(checkOutDate.getDate() + 1);
      const checkOutStr = checkOutDate.toISOString().split('T')[0];

      console.log(`      🔗 TBO API Call for this route:\n`);
      console.log(`         POST https://affiliate.tektravels.com/HotelAPI/Search`);
      console.log(`         Authorization: Basic VEJPQXBpOlRCT0FwaUAxMjM=\n`);
      console.log(`         Body:`);
      console.log(`         {`);
      console.log(`           "CheckIn": "${routeDate}",`);
      console.log(`           "CheckOut": "${checkOutStr}",`);
      console.log(`           "CityCode": "${city.tbo_city_code}",`);
      console.log(`           "GuestNationality": "IN",`);
      console.log(`           "PaxRooms": [{"Adults": 2, "Children": 0, "ChildrenAges": []}],`);
      console.log(`           "ResponseTime": 23.0,`);
      console.log(`           "IsDetailedResponse": true,`);
      console.log(`           "Filters": {"Refundable": true, "NoOfRooms": 0, "MealType": "WithMeal", "OrderBy": 0, "StarRating": 0}`);
      console.log(`         }\n`);
    }

    // STEP 5: Show final response structure
    console.log(`\n💾 STEP 4: Response Structure\n`);
    console.log(`   The API returns: ItineraryHotelDetailsResponseDto`);
    console.log(`   {`);
    console.log(`     "quoteId": "${quoteId}",`);
    console.log(`     "planId": ${planId},`);
    console.log(`     "hotelRatesVisible": true,`);
    console.log(`     "hotelTabs": [`);
    console.log(`       { "groupType": 1, "label": "Budget Hotels", "totalAmount": <sum of budget hotels> },`);
    console.log(`       { "groupType": 2, "label": "Mid-Range Hotels", "totalAmount": <sum> },`);
    console.log(`       { "groupType": 3, "label": "Premium Hotels", "totalAmount": <sum> },`);
    console.log(`       { "groupType": 4, "label": "Luxury Hotels", "totalAmount": <sum> }`);
    console.log(`     ],`);
    console.log(`     "hotels": [`);
    console.log(`       { "groupType": 1, "itineraryRouteId": ..., "day": "Day 1 | 2026-02-15", "destination": "...", "hotelName": "...", "price": ... },`);
    console.log(`       ...`);
    console.log(`     ],`);
    console.log(`     "totalRoomCount": <number of hotel entries>`);
    console.log(`   }\n`);

  } catch (error) {
    console.error(`❌ Error:`, error.message);
  } finally {
    await prisma.$disconnect();
  }
}

analyzeItineraryFlow();
