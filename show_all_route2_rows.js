const { PrismaClient } = require('@prisma/client');
const prisma = new PrismaClient();

async function showAllRoute2Rows() {
  console.log('\n=== ALL ROWS FOR ROUTE 2 (Plan 2 vs Plan 5) ===\n');
  
  // Get Plan 2 Route 2
  const plan2Routes = await prisma.dvi_itinerary_route_details.findMany({
    where: { itinerary_plan_ID: 2, deleted: 0, status: 1 },
    orderBy: { itinerary_route_ID: 'asc' },
  });
  const route2PHP = plan2Routes[1];
  
  // Get Plan 5 Route 2
  const plan5Routes = await prisma.dvi_itinerary_route_details.findMany({
    where: { itinerary_plan_ID: 5, deleted: 0, status: 1 },
    orderBy: { itinerary_route_ID: 'asc' },
  });
  const route2Nest = plan5Routes[1];
  
  console.log('=== PHP ROUTE 2 (Plan 2, Route', route2PHP.itinerary_route_ID, ') - ALL TIMELINE ROWS ===');
  const phpRows = await prisma.dvi_itinerary_route_hotspot_details.findMany({
    where: {
      itinerary_plan_ID: 2,
      itinerary_route_ID: route2PHP.itinerary_route_ID
    },
    orderBy: { hotspot_order: 'asc' }
  });
  
  phpRows.forEach(row => {
    const itemTypeNames = {1: 'Refresh', 3: 'Travel', 4: 'Stay', 5: 'Parking', 6: 'Hotel', 7: 'End'};
    const typeName = itemTypeNames[row.item_type] || row.item_type;
    console.log(`  Order ${String(row.hotspot_order).padStart(2)} | Type: ${typeName.padEnd(8)} | Hotspot: ${String(row.hotspot_ID || 0).padStart(3)} | ${row.hotspot_start_time} → ${row.hotspot_end_time}`);
  });
  
  console.log('\n=== NESTJS ROUTE 2 (Plan 5, Route', route2Nest.itinerary_route_ID, ') - ALL TIMELINE ROWS ===');
  const nestRows = await prisma.dvi_itinerary_route_hotspot_details.findMany({
    where: {
      itinerary_plan_ID: 5,
      itinerary_route_ID: route2Nest.itinerary_route_ID
    },
    orderBy: { hotspot_order: 'asc' }
  });
  
  if (nestRows.length === 0) {
    console.log('  ❌ No rows found!');
  } else {
    nestRows.forEach(row => {
      const itemTypeNames = {1: 'Refresh', 3: 'Travel', 4: 'Stay', 5: 'Parking', 6: 'Hotel', 7: 'End'};
      const typeName = itemTypeNames[row.item_type] || row.item_type;
      console.log(`  Order ${String(row.hotspot_order).padStart(2)} | Type: ${typeName.padEnd(8)} | Hotspot: ${String(row.hotspot_ID || 0).padStart(3)} | ${row.hotspot_start_time} → ${row.hotspot_end_time}`);
    });
  }
  
  await prisma.$disconnect();
}

showAllRoute2Rows().catch(console.error);
