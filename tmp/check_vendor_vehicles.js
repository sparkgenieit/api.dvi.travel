const { PrismaClient } = require('@prisma/client');
const prisma = new PrismaClient();

async function checkVendorVehicles() {
  console.log('=== PLAN 2 VENDOR VEHICLES ===\n');
  const p2Vehicles = await prisma.dvi_itinerary_plan_vendor_vehicle_details.findMany({
    where: { itinerary_plan_id: 2 },
    select: {
      itinerary_route_date: true,
      vehicle_type_id: true,
      vendor_id: true,
      total_travelled_km: true,
      total_vehicle_amount: true,
    }
  });
  console.log(JSON.stringify(p2Vehicles, null, 2));

  console.log('\n=== PLAN 5 VENDOR VEHICLES ===\n');
  const p5Vehicles = await prisma.dvi_itinerary_plan_vendor_vehicle_details.findMany({
    where: { itinerary_plan_id: 5 },
    select: {
      itinerary_route_date: true,
      vehicle_type_id: true,
      vendor_id: true,
      total_travelled_km: true,
      total_vehicle_amount: true,
    }
  });
  console.log(JSON.stringify(p5Vehicles, null, 2));

  console.log('\n=== ANALYSIS ===');
  console.log(`Plan 2: ${p2Vehicles.length} vehicles across 2 routes`);
  console.log(`Plan 5: ${p5Vehicles.length} vehicles across 3 routes`);
  console.log('\nPlan 2 has 2 routes, Plan 5 has 3 routes.');
  console.log('Expected: Plan 5 should have MORE vehicles (proportionally)');
  console.log(`Actual: Plan 5 has ${p5Vehicles.length}, expected ~9 (3/2 * 6)`);

  await prisma.$disconnect();
}

checkVendorVehicles().catch(console.error);
