const { PrismaClient } = require('@prisma/client');
const prisma = new PrismaClient();

async function compareRouteStartTimes() {
  try {
    const php = await prisma.$queryRaw`
      SELECT 
        itinerary_route_ID,
        TIME_FORMAT(route_start_time, '%H:%i:%s') as start_time,
        TIME_FORMAT(route_end_time, '%H:%i:%s') as end_time
      FROM dvi_itinerary_route_details
      WHERE itinerary_route_ID = 178
    `;

    const nestjs = await prisma.$queryRaw`
      SELECT 
        itinerary_route_ID,
        TIME_FORMAT(route_start_time, '%H:%i:%s') as start_time,
        TIME_FORMAT(route_end_time, '%H:%i:%s') as end_time
      FROM dvi_itinerary_route_details
      WHERE itinerary_route_ID = 391
    `;

    console.log('\n=== ROUTE START/END TIMES ===\n');
    console.log(`PHP Route 178:    ${php[0].start_time} - ${php[0].end_time}`);
    console.log(`NestJS Route 391: ${nestjs[0].start_time} - ${nestjs[0].end_time}`);

    if (php[0].start_time !== nestjs[0].start_time) {
      console.log('\n❌ DIFFERENT START TIMES!');
      console.log('PHP and NestJS plans have different trip schedules');
    }

  } finally {
    await prisma.$disconnect();
  }
}

compareRouteStartTimes().catch(console.error);
