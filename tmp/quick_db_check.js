const { PrismaClient } = require('@prisma/client');
const p = new PrismaClient();

Promise.all([
  p.dvi_itinerary_plan_vendor_eligible_list.count({where:{itinerary_plan_id:5}}),
  p.dvi_itinerary_route_hotspot_parking_charge.count({where:{itinerary_plan_ID:5}}),
  p.dvi_itinerary_plan_hotel_room_details.findFirst({where:{itinerary_plan_id:5},select:{hotel_id:true,room_rate:true}})
]).then(([vendor,parking,hotel]) => {
  console.log('\n=== CURRENT PLAN 5 STATE ===');
  console.log('Vendor eligible list:', vendor, 'rows');
  console.log('Parking charges:', parking, 'rows');
  console.log('Hotel data:', hotel);
  console.log('\nNote: Run trigger_optimization.js to regenerate plan 5');
}).finally(() => p.$disconnect());
