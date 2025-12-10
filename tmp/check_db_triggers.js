const { PrismaClient } = require('@prisma/client');
const prisma = new PrismaClient();

async function main() {
  // Check for any triggers on the table
  const triggers = await prisma.$queryRaw`
    SELECT TRIGGER_NAME, EVENT_MANIPULATION, ACTION_STATEMENT 
    FROM information_schema.TRIGGERS 
    WHERE EVENT_OBJECT_TABLE = 'dvi_itinerary_route_hotspot_details'
    AND EVENT_OBJECT_SCHEMA = 'dvi_travels'
  `;

  console.log('\n=== Triggers on dvi_itinerary_route_hotspot_details ===');
  console.log(JSON.stringify(triggers, null, 2));

  await prisma.$disconnect();
}

main();
