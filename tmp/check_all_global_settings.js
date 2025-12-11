const { PrismaClient } = require('@prisma/client');
const prisma = new PrismaClient();

async function main() {
  const rows = await prisma.dvi_global_settings.findMany();
  
  console.log('\n=== All Global Settings Rows ===');
  console.log(`Total rows: ${rows.length}\n`);
  
  rows.forEach(r => {
    console.log(`ID: ${r.global_ID}`);
    console.log(`  Status: ${r.status}, Deleted: ${r.deleted}`);
    console.log(`  Local speed: ${r.itinerary_local_speed_limit} km/h`);
    console.log(`  Outstation speed: ${r.itinerary_outstation_speed_limit} km/h`);
    console.log('');
  });
}

main()
  .catch(console.error)
  .finally(() => prisma.$disconnect());
