const { PrismaClient } = require('@prisma/client');
const prisma = new PrismaClient();

async function deleteRoutes() {
  try {
    await prisma.dvi_itinerary_route_hotspot_details.updateMany({
      where: { itinerary_plan_ID: 13 },
      data: { deleted: 1 },
    });

    await prisma.dvi_itinerary_route_details.updateMany({
      where: { itinerary_plan_ID: 13 },
      data: { deleted: 1 },
    });

    console.log('✅ Deleted all routes for Plan 13');
    await prisma.$disconnect();
  } catch (error) {
    console.error('Error:', error);
    await prisma.$disconnect();
  }
}

deleteRoutes();
