const { PrismaClient } = require('@prisma/client');
const prisma = new PrismaClient();

async function checkAllRowTypes() {
  const rows = await prisma.dvi_itinerary_route_hotspot_details.findMany({
    where: {
      itinerary_plan_ID: 2,
      itinerary_route_ID: 429,
      deleted: 0
    },
    orderBy: { hotspot_order: 'asc' }
  });
  
  console.log(`Total rows: ${rows.length}\n`);
  
  const typeNames = {1: 'Refresh', 3: 'Travel', 4: 'Visit', 5: 'ToHotel', 6: 'AtHotel', 7: 'Return'};
  const typeCounts = {};
  rows.forEach(r => {
    typeCounts[r.item_type] = (typeCounts[r.item_type] || 0) + 1;
  });
  
  console.log('Row type counts:');
  Object.entries(typeCounts).forEach(([type, count]) => {
    console.log(`  Type ${type} (${typeNames[type]}): ${count}`);
  });
  
  console.log('\nChecking for item_type=1 (Refresh):');
  const refresh = rows.filter(r => r.item_type === 1);
  console.log(`Found ${refresh.length} Refresh rows`);
  
  await prisma.$disconnect();
}

checkAllRowTypes();
