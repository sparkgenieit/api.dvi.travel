const mysql = require('mysql2/promise');

async function checkPHPSourceFiltering() {
  const connection = await mysql.createConnection({
    host: 'localhost',
    user: 'root',
    password: 'root',
    database: 'dvi'
  });

  console.log('\n=== CHECKING PHP SOURCE HOTSPOT FILTERING ===\n');

  // Get Route 2 from Plan 2 (PHP)
  const [phpRoute] = await connection.execute(`
    SELECT * FROM itinerary_routes 
    WHERE itinerary_ID = 2 AND order_in_itinerary = 2
  `);
  
  console.log('Route 2 (PHP Plan 2):');
  console.log(`  Route ID: ${phpRoute[0].route_ID}`);
  console.log(`  ${phpRoute[0].target_location} → ${phpRoute[0].next_location}`);
  console.log(`  direct_to_next_visiting_place: ${phpRoute[0].direct_to_next_visiting_place}`);
  console.log(`  target_location_id: ${phpRoute[0].target_location_id}`);
  console.log(`  next_location_id: ${phpRoute[0].next_location_id}`);

  // Get all Chennai hotspots (source location)
  const [chennaiHotspots] = await connection.execute(`
    SELECT 
      h.hotspot_ID,
      h.hotspot_name,
      h.hotspot_priority,
      hl.location_id,
      l.location_name,
      h.hotspot_latitude,
      h.hotspot_longitude
    FROM hotspots h
    LEFT JOIN hotspots_locations hl ON h.hotspot_ID = hl.hotspot_id
    LEFT JOIN locations l ON hl.location_id = l.location_ID
    WHERE hl.location_id = ${phpRoute[0].target_location_id}
    ORDER BY h.hotspot_priority, h.hotspot_ID
  `);

  console.log('\n=== ALL CHENNAI HOTSPOTS (Source Location) ===');
  chennaiHotspots.forEach(h => {
    const isSelected = [4].includes(h.hotspot_ID);
    console.log(`  ID=${h.hotspot_ID.toString().padStart(3)} Priority=${(h.hotspot_priority || 0).toString().padStart(2)} "${h.hotspot_name}" ${isSelected ? '✅ SELECTED' : '❌ NOT SELECTED'}`);
  });

  // Get all Pondicherry hotspots (destination location)
  const [pondyHotspots] = await connection.execute(`
    SELECT 
      h.hotspot_ID,
      h.hotspot_name,
      h.hotspot_priority,
      hl.location_id,
      l.location_name
    FROM hotspots h
    LEFT JOIN hotspots_locations hl ON h.hotspot_ID = hl.hotspot_id
    LEFT JOIN locations l ON hl.location_id = l.location_ID
    WHERE hl.location_id = ${phpRoute[0].next_location_id}
    ORDER BY h.hotspot_priority, h.hotspot_ID
  `);

  console.log('\n=== ALL PONDICHERRY HOTSPOTS (Destination Location) ===');
  pondyHotspots.forEach(h => {
    const isSelected = [18, 21, 19, 17, 678].includes(h.hotspot_ID);
    console.log(`  ID=${h.hotspot_ID.toString().padStart(3)} Priority=${(h.hotspot_priority || 0).toString().padStart(2)} "${h.hotspot_name}" ${isSelected ? '✅ SELECTED' : '❌ NOT SELECTED'}`);
  });

  // Check actual selected hotspots from timeline
  const [timeline] = await connection.execute(`
    SELECT hotspot_ID, item_type, location_id, \`order\`
    FROM itinerary_route_timeline
    WHERE route_ID = ${phpRoute[0].route_ID}
    ORDER BY \`order\`
  `);

  console.log('\n=== ACTUAL PHP TIMELINE HOTSPOTS ===');
  timeline.forEach(row => {
    if (row.hotspot_ID) {
      const hotspot = [...chennaiHotspots, ...pondyHotspots].find(h => h.hotspot_ID === row.hotspot_ID);
      if (hotspot) {
        const location = hotspot.location_id === phpRoute[0].target_location_id ? 'SOURCE' : 'DEST';
        console.log(`  Order=${row.order.toString().padStart(2)} ID=${row.hotspot_ID.toString().padStart(3)} Priority=${(hotspot.hotspot_priority || 0).toString().padStart(2)} ${location.padEnd(6)} "${hotspot.hotspot_name}"`);
      }
    }
  });

  // Analysis
  const sourceHotspots = timeline
    .filter(r => r.hotspot_ID)
    .map(r => [...chennaiHotspots, ...pondyHotspots].find(h => h.hotspot_ID === r.hotspot_ID))
    .filter(h => h && h.location_id === phpRoute[0].target_location_id);

  const destHotspots = timeline
    .filter(r => r.hotspot_ID)
    .map(r => [...chennaiHotspots, ...pondyHotspots].find(h => h.hotspot_ID === r.hotspot_ID))
    .filter(h => h && h.location_id === phpRoute[0].next_location_id);

  console.log('\n=== PHP SOURCE/DEST SELECTION ANALYSIS ===');
  console.log(`SOURCE (Chennai) hotspots selected: ${sourceHotspots.length}`);
  sourceHotspots.forEach(h => {
    console.log(`  - ID=${h.hotspot_ID} Priority=${h.hotspot_priority || 0} "${h.hotspot_name}"`);
  });

  console.log(`\nDESTINATION (Pondicherry) hotspots selected: ${destHotspots.length}`);
  destHotspots.forEach(h => {
    console.log(`  - ID=${h.hotspot_ID} Priority=${h.hotspot_priority || 0} "${h.hotspot_name}"`);
  });

  // Check if PHP filters priority 0 from SOURCE
  const chennaiPriority0 = chennaiHotspots.filter(h => (h.hotspot_priority || 0) === 0);
  const sourcePriority0Selected = sourceHotspots.filter(h => (h.hotspot_priority || 0) === 0);

  console.log('\n=== PRIORITY 0 FILTERING ===');
  console.log(`Chennai has ${chennaiPriority0.length} priority-0 hotspots`);
  console.log(`PHP selected ${sourcePriority0Selected.length} priority-0 SOURCE hotspots`);
  
  if (chennaiPriority0.length > 0 && sourcePriority0Selected.length === 0) {
    console.log('✅ CONCLUSION: PHP FILTERS OUT priority-0 SOURCE hotspots!');
  } else {
    console.log('❌ Priority-0 filtering is NOT the issue');
  }

  await connection.end();
}

checkPHPSourceFiltering().catch(console.error);
