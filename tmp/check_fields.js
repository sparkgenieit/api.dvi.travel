const { PrismaClient } = require('@prisma/client');
const prisma = new PrismaClient();

prisma.dvi_itinerary_plan_vehicle_details.findFirst({where:{itinerary_plan_id:2}})
  .then(r => {
    console.log('Available fields:');
    console.log(Object.keys(r || {}));
  })
  .finally(() => prisma.$disconnect());
