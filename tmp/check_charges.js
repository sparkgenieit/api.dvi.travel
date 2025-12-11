const { PrismaClient } = require('@prisma/client');
const p = new PrismaClient();

Promise.all([
  p.dvi_itinerary_route_hotspot_parking_charge.count({where:{itinerary_plan_ID:2}}),
  p.dvi_itinerary_route_hotspot_parking_charge.count({where:{itinerary_plan_ID:5}}),
  p.dvi_itinerary_plan_vendor_vehicle_details.findFirst({where:{itinerary_plan_id:2}}),
  p.dvi_itinerary_plan_vendor_vehicle_details.findFirst({where:{itinerary_plan_id:5}})
]).then(([p2parking, p5parking, p2vendor, p5vendor]) => {
  console.log('Plan 2 parking charges:', p2parking);
  console.log('Plan 5 parking charges:', p5parking);
  console.log('\nPlan 2 vendor vehicle:');
  console.log('- parking:', p2vendor?.vehicle_parking_charges);
  console.log('- toll:', p2vendor?.vehicle_toll_charges);
  console.log('- permit:', p2vendor?.vehicle_permit_charges);
  console.log('\nPlan 5 vendor vehicle:');
  console.log('- parking:', p5vendor?.vehicle_parking_charges);
  console.log('- toll:', p5vendor?.vehicle_toll_charges);
  console.log('- permit:', p5vendor?.vehicle_permit_charges);
}).finally(() => p.$disconnect());
