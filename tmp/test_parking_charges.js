const { PrismaClient } = require('@prisma/client');
const prisma = new PrismaClient();

async function main() {
  console.log('\n=== PARKING CHARGES TEST ===\n');
  
  // Check if plan 5 has parking charges
  const parkingCharges = await prisma.dvi_itinerary_route_hotspot_parking_charge.findMany({
    where: { itinerary_plan_ID: 5 }
  });
  
  console.log(`Plan 5 parking charges: ${parkingCharges.length} rows`);
  
  if (parkingCharges.length > 0) {
    const total = parkingCharges.reduce((sum, pc) => sum + Number(pc.parking_charges_amt || 0), 0);
    console.log(`Total parking charges: ${total}`);
  }
  
  // Check plan 5 vehicle details
  const vehicleDetails = await prisma.dvi_itinerary_plan_vehicle_details.findFirst({
    where: { itinerary_plan_id: 5 }
  });
  
  if (vehicleDetails) {
    console.log('\nPlan 5 vehicle details:');
    console.log(`- vehicle_parking_charges: ${vehicleDetails.vehicle_parking_charges}`);
    console.log(`- vehicle_toll_charges: ${vehicleDetails.vehicle_toll_charges}`);
    console.log(`- vehicle_permit_charges: ${vehicleDetails.vehicle_permit_charges}`);
  }
  
  // Check plan 2 for comparison
  const plan2Vehicle = await prisma.dvi_itinerary_plan_vehicle_details.findFirst({
    where: { itinerary_plan_id: 2 }
  });
  
  if (plan2Vehicle) {
    console.log('\nPlan 2 vehicle details (for comparison):');
    console.log(`- vehicle_parking_charges: ${plan2Vehicle.vehicle_parking_charges}`);
    console.log(`- vehicle_toll_charges: ${plan2Vehicle.vehicle_toll_charges}`);
    console.log(`- vehicle_permit_charges: ${plan2Vehicle.vehicle_permit_charges}`);
  }
}

main()
  .catch(e => {
    console.error('ERROR:', e.message);
    process.exit(1);
  })
  .finally(() => prisma.$disconnect());
