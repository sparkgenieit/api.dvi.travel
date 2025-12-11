const { PrismaClient } = require('@prisma/client');
const prisma = new PrismaClient();

async function main() {
  console.log('Testing field names in dvi_itinerary_route_hotspot_details...\n');
  
  // Get a sample row to see actual field names
  const sample = await prisma.dvi_itinerary_route_hotspot_details.findFirst({
    where: { itinerary_plan_ID: 2 }
  });
  
  if (sample) {
    console.log('Available fields:');
    console.log(Object.keys(sample));
    console.log('\nSample data:');
    console.log(sample);
  } else {
    console.log('No data found');
  }
}

main()
  .catch(e => console.error('ERROR:', e.message))
  .finally(() => prisma.$disconnect());
