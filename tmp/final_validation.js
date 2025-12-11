const { PrismaClient } = require('@prisma/client');
const prisma = new PrismaClient();

async function main() {
  console.log('\n=== FINAL VALIDATION: Plan 2 vs Plan 5 ===\n');

  // 1. Vendor eligible list
  const p2eligible = await prisma.dvi_itinerary_plan_vendor_eligible_list.count({where:{itinerary_plan_id:2}});
  const p5eligible = await prisma.dvi_itinerary_plan_vendor_eligible_list.count({where:{itinerary_plan_id:5}});
  console.log(`✓ Vendor Eligible List: Plan 2=${p2eligible}, Plan 5=${p5eligible}`, p2eligible === p5eligible ? '✅' : '❌');

  // 2. Vendor vehicle details
  const p2vendor = await prisma.dvi_itinerary_plan_vendor_vehicle_details.count({where:{itinerary_plan_id:2}});
  const p5vendor = await prisma.dvi_itinerary_plan_vendor_vehicle_details.count({where:{itinerary_plan_id:5}});
  console.log(`✓ Vendor Vehicle Details: Plan 2=${p2vendor}, Plan 5=${p5vendor}`, p2vendor === p5vendor ? '✅' : '❌');

  // 3. Parking charges
  const p2parking = await prisma.dvi_itinerary_route_hotspot_parking_charge.count({where:{itinerary_plan_ID:2}});
  const p5parking = await prisma.dvi_itinerary_route_hotspot_parking_charge.count({where:{itinerary_plan_ID:5}});
  console.log(`✓ Parking Charges: Plan 2=${p2parking}, Plan 5=${p5parking}`, p2parking === p5parking ? '✅' : '❌');

  // 4. Hotel details
  const p2hotel = await prisma.dvi_itinerary_plan_hotel_details.count({where:{itinerary_plan_id:2}});
  const p5hotel = await prisma.dvi_itinerary_plan_hotel_details.count({where:{itinerary_plan_id:5}});
  console.log(`✓ Hotel Details: Plan 2=${p2hotel}, Plan 5=${p5hotel}`, p2hotel === p5hotel ? '✅' : '❌');

  // 5. Hotel room details
  const p2rooms = await prisma.dvi_itinerary_plan_hotel_room_details.count({where:{itinerary_plan_id:2}});
  const p5rooms = await prisma.dvi_itinerary_plan_hotel_room_details.count({where:{itinerary_plan_id:5}});
  console.log(`✓ Hotel Room Details: Plan 2=${p2rooms}, Plan 5=${p5rooms}`, p2rooms === p5rooms ? '✅' : '❌');

  // 6. Check critical field values
  console.log('\n=== Field Value Checks ===\n');

  const p5vendorVehicle = await prisma.dvi_itinerary_plan_vendor_vehicle_details.findFirst({
    where:{itinerary_plan_id:5},
    select: {
      vehicle_parking_charges: true,
      vehicle_toll_charges: true,
      vehicle_permit_charges: true,
      vehicle_gst_type: true,
      vehicle_gst_percentage: true,
      vehicle_gst_amount: true,
      vendor_margin_percentage: true,
      vendor_margin_gst_amount: true
    }
  });

  console.log('Plan 5 Vendor Vehicle:');
  console.log(`  - parking_charges: ${p5vendorVehicle?.vehicle_parking_charges} (should be > 0)`);
  console.log(`  - toll_charges: ${p5vendorVehicle?.vehicle_toll_charges}`);
  console.log(`  - permit_charges: ${p5vendorVehicle?.vehicle_permit_charges}`);
  console.log(`  - gst_type: ${p5vendorVehicle?.vehicle_gst_type} (should be 2)`);
  console.log(`  - gst_percentage: ${p5vendorVehicle?.vehicle_gst_percentage} (should be 5)`);
  console.log(`  - gst_amount: ${p5vendorVehicle?.vehicle_gst_amount} (should be > 0)`);
  console.log(`  - vendor_margin_%: ${p5vendorVehicle?.vendor_margin_percentage} (should be 10)`);
  console.log(`  - vendor_margin_gst: ${p5vendorVehicle?.vendor_margin_gst_amount} (should be > 0)`);

  const p5hotel = await prisma.dvi_itinerary_plan_hotel_room_details.findFirst({
    where:{itinerary_plan_id:5},
    select: {
      hotel_id: true,
      room_id: true,
      room_rate: true,
      total_breafast_cost: true
    }
  });

  console.log('\nPlan 5 Hotel Room:');
  console.log(`  - hotel_id: ${p5hotel?.hotel_id} (should be > 0)`);
  console.log(`  - room_id: ${p5hotel?.room_id} (should be > 0)`);
  console.log(`  - room_rate: ${p5hotel?.room_rate} (should be > 0)`);
  console.log(`  - breakfast_cost: ${p5hotel?.total_breafast_cost} (should be > 0)`);

  console.log('\n=== Summary ===');
  console.log('If all checks show ✅ and values are > 0, implementation is COMPLETE!');
  console.log('Run node tmp/compare_plan_data.js for detailed field comparison.');
}

main()
  .catch(e => {
    console.error('ERROR:', e.message);
    process.exit(1);
  })
  .finally(() => prisma.$disconnect());
