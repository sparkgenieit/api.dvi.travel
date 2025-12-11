const { PrismaClient } = require('@prisma/client');
const prisma = new PrismaClient();

async function getTableSchema(tableName) {
  console.log(`\n${'='.repeat(80)}`);
  console.log(`Schema for: ${tableName}`);
  console.log('='.repeat(80));

  try {
    const columns = await prisma.$queryRawUnsafe(
      `DESCRIBE ${tableName}`
    );
    
    console.table(columns);
  } catch (error) {
    console.error(`Error getting schema for ${tableName}:`, error.message);
  }
}

async function main() {
  const tables = [
    'dvi_itinerary_traveller_details',
    'dvi_itinerary_plan_hotel_details',
    'dvi_itinerary_plan_hotel_room_details',
    'dvi_itinerary_plan_vehicle_details',
    'dvi_itinerary_plan_vendor_eligible_list',
    'dvi_itinerary_plan_vendor_vehicle_details',
  ];

  for (const table of tables) {
    await getTableSchema(table);
  }
}

main()
  .catch(console.error)
  .finally(() => prisma.$disconnect());
