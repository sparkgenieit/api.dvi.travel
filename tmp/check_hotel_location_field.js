const { PrismaClient } = require('@prisma/client');
const prisma = new PrismaClient();

async function checkHotelLocationField() {
  console.log('=== PLAN 2 HOTEL LOCATIONS (from hotel_details table) ===\n');
  const p2Hotels = await prisma.$queryRaw`
    SELECT 
      itinerary_route_id,
      itinerary_route_location,
      group_type,
      hotel_id
    FROM dvi_itinerary_plan_hotel_details
    WHERE itinerary_plan_id = 2
    ORDER BY itinerary_route_id, group_type
    LIMIT 10
  `;
  
  console.log(JSON.stringify(p2Hotels, null, 2));

  console.log('\n=== PLAN 2 ROUTES (from route_details table) ===\n');
  const p2Routes = await prisma.$queryRaw`
    SELECT 
      itinerary_route_ID,
      location_name,
      next_visiting_location
    FROM dvi_itinerary_route_details
    WHERE itinerary_plan_ID = 2
    ORDER BY itinerary_route_ID
  `;
  
  console.log(JSON.stringify(p2Routes, null, 2));

  await prisma.$disconnect();
}

checkHotelLocationField().catch(console.error);
