const { PrismaClient } = require('@prisma/client');
const prisma = new PrismaClient();

(async () => {
  const p2Routes = await prisma.dvi_itinerary_route_details.count({ where: { itinerary_plan_ID: 2 } });
  const p5Routes = await prisma.dvi_itinerary_route_details.count({ where: { itinerary_plan_ID: 5 } });
  console.log('Routes: Plan 2 =', p2Routes, ', Plan 5 =', p5Routes);
  
  const p2Vehicles = await prisma.dvi_itinerary_plan_vehicle_details.count({ where: { itinerary_plan_id: 2 } });
  const p5Vehicles = await prisma.dvi_itinerary_plan_vehicle_details.count({ where: { itinerary_plan_id: 5 } });
  console.log('Plan Vehicles: Plan 2 =', p2Vehicles, ', Plan 5 =', p5Vehicles);
  
  await prisma.$disconnect();
})();
