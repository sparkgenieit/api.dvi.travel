const { PrismaClient } = require('@prisma/client');
const prisma = new PrismaClient();

async function main() {
  try {
    const updateRes = await prisma.dvi_itinerary_route_details.update({
      where: {
        itinerary_route_ID: 207271,
      },
      data: {
        updatedon: new Date(),
      },
    });
    console.log('Update Result:', JSON.stringify(updateRes, (key, value) => typeof value === 'bigint' ? value.toString() : value));
    console.log('Result:', JSON.stringify(res, (key, value) => typeof value === 'bigint' ? value.toString() : value));
    
    const count = await prisma.$queryRaw`SELECT COUNT(*) as count FROM dvi_itinerary_route_details WHERE itinerary_route_ID = 207271`;
    console.log('Count:', JSON.stringify(count, (key, value) => typeof value === 'bigint' ? value.toString() : value));

    const sample = await prisma.$queryRaw`SELECT itinerary_route_ID, itinerary_plan_ID FROM dvi_itinerary_route_details LIMIT 1`;
    console.log('Sample:', JSON.stringify(sample, (key, value) => typeof value === 'bigint' ? value.toString() : value));
  } catch (e) {
    console.error(e);
  } finally {
    await prisma.$disconnect();
  }
}

main();
