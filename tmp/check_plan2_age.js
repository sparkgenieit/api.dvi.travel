const { PrismaClient } = require('@prisma/client');
const prisma = new PrismaClient();

async function main() {
  const plan2 = await prisma.dvi_itinerary_plan_details.findUnique({
    where: { itinerary_plan_ID: 2 },
    select: {
      createdby: true,
      createdon: true,
      updatedon: true,
    }
  });

  console.log('\n=== PHP Plan 2 Metadata ===');
  console.log('Created by:', plan2.createdby);
  console.log('Created on:', plan2.createdon);
  console.log('Updated on:', plan2.updatedon);

  const route179 = await prisma.dvi_itinerary_route_details.findUnique({
    where: { itinerary_route_ID: 179 },
    select: {
      createdby: true,
      createdon: true,
      updatedon: true,
    }
  });

  console.log('\n=== Route 179 Metadata ===');
  console.log('Created by:', route179.createdby);
  console.log('Created on:', route179.createdon);
  console.log('Updated on:', route179.updatedon);
  
  console.log('\n=== Analysis ===');
  const now = new Date();
  const ageMs = now - route179.createdon;
  const ageDays = ageMs / (1000 * 60 * 60 * 24);
  console.log(`Route is ${ageDays.toFixed(1)} days old`);
  
  if (ageDays > 7) {
    console.log('\n⚠️  Route is old! It may have been generated with different settings.');
    console.log('The 34-minute travel time might be from old/different configuration.');
  } else {
    console.log('\n✅ Route is recent, should reflect current settings.');
  }
}

main()
  .catch(console.error)
  .finally(() => prisma.$disconnect());
