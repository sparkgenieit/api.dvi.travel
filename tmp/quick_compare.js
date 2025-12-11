const { PrismaClient } = require('@prisma/client');
const prisma = new PrismaClient();

async function quickCompare() {
  console.log('\n=== VENDOR ELIGIBLE LIST COMPARISON ===\n');
  
  const plan2 = await prisma.$queryRawUnsafe(
    'SELECT vehicle_gst_type, vehicle_gst_percentage, vehicle_gst_amount, vendor_margin_percentage, vendor_margin_amount, vehicle_grand_total FROM dvi_itinerary_plan_vendor_eligible_list WHERE itinerary_plan_id = 2 LIMIT 1'
  );
  
  const plan5 = await prisma.$queryRawUnsafe(
    'SELECT vehicle_gst_type, vehicle_gst_percentage, vehicle_gst_amount, vendor_margin_percentage, vendor_margin_amount, vehicle_grand_total FROM dvi_itinerary_plan_vendor_eligible_list WHERE itinerary_plan_id = 5 LIMIT 1'
  );
  
  console.log('Plan 2 (PHP):', plan2[0]);
  console.log('\nPlan 5 (NestJS):', plan5[0]);
  
  console.log('\n=== HOTEL ROOM DETAILS SAMPLE ===\n');
  
  const hotel2 = await prisma.$queryRawUnsafe(
    'SELECT hotel_id, room_type_id, room_id, room_rate FROM dvi_itinerary_plan_hotel_room_details WHERE itinerary_plan_id = 2 LIMIT 1'
  );
  
  const hotel5 = await prisma.$queryRawUnsafe(
    'SELECT hotel_id, room_type_id, room_id, room_rate FROM dvi_itinerary_plan_hotel_room_details WHERE itinerary_plan_id = 5 LIMIT 1'
  );
  
  console.log('Plan 2 (PHP):', hotel2[0]);
  console.log('\nPlan 5 (NestJS):', hotel5[0]);
}

quickCompare()
  .catch(console.error)
  .finally(() => prisma.$disconnect());
