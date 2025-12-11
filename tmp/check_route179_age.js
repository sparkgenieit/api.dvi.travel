const { PrismaClient } = require('@prisma/client');
const prisma = new PrismaClient();

async function main() {
  // Check Route 179 creation date
  const route179 = await prisma.dvi_itinerary_route_details.findUnique({
    where: { itinerary_route_ID: 179 },
    select: {
      itinerary_route_ID: true,
      itinerary_plan_ID: true,
      location_name: true,
      createdon: true,
      updatedon: true
    }
  });

  console.log('\n=== ROUTE 179 METADATA ===\n');
  console.log(`Route ID: ${route179.itinerary_route_ID}`);
  console.log(`Plan ID: ${route179.itinerary_plan_ID}`);
  console.log(`Location: ${route179.location_name}`);
  console.log(`Created: ${route179.createdon.toISOString()}`);
  console.log(`Updated: ${route179.updatedon?.toISOString() || 'Never'}`);

  // Calculate age
  const now = new Date();
  const ageMs = now - route179.createdon;
  const ageHours = ageMs / (1000 * 60 * 60);
  const ageDays = ageHours / 24;

  console.log(`\nAge: ${ageDays.toFixed(2)} days (${ageHours.toFixed(2)} hours)`);

  // If it was JUST created with wrong speed, let me trigger a new PHP optimization
  console.log('\n⚠️  Route 179 is', ageDays > 5 ? 'OLD' : 'RECENT');
  console.log('This might be from when speed settings were different or a bug in PHP calculation.');
}

main()
  .catch(console.error)
  .finally(() => prisma.$disconnect());
