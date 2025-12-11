const { PrismaClient } = require('@prisma/client');
const prisma = new PrismaClient();

async function checkParkingValues() {
  console.log('\n=== PLAN 5 PARKING CHARGES (sample 5) ===\n');
  
  const plan5Charges = await prisma.dvi_itinerary_route_hotspot_parking_charge.findMany({
    where: { itinerary_plan_ID: 5 },
    take: 5,
    select: {
      hotspot_ID: true,
      vehicle_type: true,
      vehicle_qty: true,
      parking_charges_amt: true
    }
  });
  
  console.log(JSON.stringify(plan5Charges, null, 2));
  
  console.log('\n=== PLAN 2 PARKING CHARGES (sample 5) ===\n');
  
  const plan2Charges = await prisma.dvi_itinerary_route_hotspot_parking_charge.findMany({
    where: { itinerary_plan_ID: 2 },
    take: 5,
    select: {
      hotspot_ID: true,
      vehicle_type: true,
      vehicle_qty: true,
      parking_charges_amt: true
    }
  });
  
  console.log(JSON.stringify(plan2Charges, null, 2));
  
  await prisma.$disconnect();
}

checkParkingValues().catch(console.error);
