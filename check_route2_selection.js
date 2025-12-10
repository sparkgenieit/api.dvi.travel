const { PrismaClient } = require('@prisma/client');
const prisma = new PrismaClient();

async function checkRoute2HotspotSelection() {
  // Get route details
  const route = await prisma.$queryRawUnsafe(`
    SELECT * FROM dvi_itinerary_route_details
    WHERE itinerary_route_ID = 401
  `);

  console.log('\n=== ROUTE 401 DETAILS ===');
  console.log('Location ID:', route[0].location_id);
  console.log('Location Name:', route[0].location_name);
  console.log('Next Visiting Location:', route[0].next_visiting_location);
  console.log('Direct:', route[0].direct_to_next_visiting_place);

  // Get all hotspots for this location combination (SOURCE hotspots)
  const sourceHotspots = await prisma.$queryRawUnsafe(`
    SELECT 
      hotspot_ID,
      hotspot_name,
      hotspot_location,
      hotspot_priority
    FROM dvi_hotspot_place
    WHERE (
      hotspot_location = '${route[0].location_name}|${route[0].next_visiting_location}'
      OR hotspot_location = '${route[0].next_visiting_location}|${route[0].location_name}'
    )
    AND status = 1
    AND deleted = 0
    ORDER BY 
      CASE WHEN hotspot_priority = 0 THEN 999 ELSE hotspot_priority END,
      hotspot_ID
  `);

  console.log(`\n=== SOURCE HOTSPOTS FOR ${route[0].location_name} → ${route[0].next_visiting_location} ===`);
  console.log(`Total: ${sourceHotspots.length}`);
  
  sourceHotspots.forEach((h, i) => {
    console.log(`${i+1}. Hotspot ${h.hotspot_ID}: ${h.hotspot_name} (Priority ${h.hotspot_priority})`);
    console.log(`   Location: ${h.hotspot_location}`);
  });

  // Check which ones are in Plan 5 Route 2
  const selected = [4, 21, 19, 17, 677, 679];
  console.log('\n=== SELECTED IN NESTJS ===');
  sourceHotspots.filter(h => selected.includes(h.hotspot_ID)).forEach(h => {
    console.log(`✅ ${h.hotspot_ID}: ${h.hotspot_name} (Priority ${h.hotspot_priority})`);
  });

  console.log('\n=== NOT SELECTED IN NESTJS ===');
  sourceHotspots.filter(h => !selected.includes(h.hotspot_ID)).forEach(h => {
    console.log(`❌ ${h.hotspot_ID}: ${h.hotspot_name} (Priority ${h.hotspot_priority})`);
  });

  await prisma.$disconnect();
}

checkRoute2HotspotSelection().catch(console.error);
