const { PrismaClient } = require('@prisma/client');
const prisma = new PrismaClient();

async function checkManualHotspots() {
  const planId = 33971;

  try {
    // 1. Get all rows for this plan from the hotspot details table (which IS the timeline)
    const allRows = await prisma.dvi_itinerary_route_hotspot_details.findMany({
      where: {
        itinerary_plan_ID: 33971,
        deleted: 0
      },
      orderBy: [
        { itinerary_route_ID: 'asc' },
        { hotspot_order: 'asc' }
      ]
    });

    // 2. Get hotspot names for mapping
    const hotspotIds = [...new Set(allRows.filter(r => r.hotspot_ID > 0).map(r => r.hotspot_ID))];
    const hotspotPlaces = await prisma.dvi_hotspot_place.findMany({
      where: {
        hotspot_ID: { in: hotspotIds }
      },
      select: {
        hotspot_ID: true,
        hotspot_name: true
      }
    });

    const hotspotMap = Object.fromEntries(hotspotPlaces.map(p => [p.hotspot_ID, p.hotspot_name]));

    console.log('\nManual Hotspots Status for Plan ' + planId + ':');
    console.log('='.repeat(120));
    console.log('Route'.padEnd(8) + ' | ' + 'ID'.padEnd(5) + ' | ' + 'Type'.padEnd(12) + ' | ' + 'Name'.padEnd(30) + ' | ' + 'Manual'.padEnd(6) + ' | ' + 'Conflict'.padEnd(8) + ' | ' + 'Reason');
    console.log('-'.repeat(120));

    for (const row of allRows) {
      // Only show manual hotspots (hotspot_plan_own_way = 1) OR actual hotspot visits (item_type = 4)
      if (row.hotspot_plan_own_way !== 1 && row.item_type !== 4) continue;

      const name = hotspotMap[row.hotspot_ID] || (row.item_type === 1 ? 'Refreshment' : row.item_type === 3 ? 'Travel' : 'Unknown');
      const typeStr = row.item_type === 1 ? 'Refresh' : row.item_type === 3 ? 'Travel' : row.item_type === 4 ? 'Visit' : 'Other';
      const manualStr = row.hotspot_plan_own_way === 1 ? 'YES' : 'NO';
      const conflictStr = row.is_conflict === 1 ? 'YES' : 'NO';
      const reason = row.conflict_reason || '-';

      console.log(
        String(row.itinerary_route_ID).padEnd(8) + ' | ' + 
        String(row.hotspot_ID || 0).padEnd(5) + ' | ' + 
        typeStr.padEnd(12) + ' | ' + 
        name.substring(0, 30).padEnd(30) + ' | ' + 
        manualStr.padEnd(6) + ' | ' + 
        conflictStr.padEnd(8) + ' | ' + 
        reason
      );
    }

  } catch (error) {
    console.error('Error:', error);
  } finally {
    await prisma.$disconnect();
  }
}

checkManualHotspots();
