const mysql = require('mysql2/promise');

async function checkPlan2Route3() {
  const connection = await mysql.createConnection({
    host: 'localhost',
    user: 'root',
    password: 'my@Richlabz123',
    database: 'dvi_travels'
  });

  try {
    console.log('\n=== PLAN 2 ROUTE 3 (Pondicherry → Pondicherry Airport) ===\n');

    // Get the route ID for Route 3
    const [routes] = await connection.execute(`
      SELECT itinerary_route_ID, location_name, next_visiting_location
      FROM dvi_itinerary_route_details
      WHERE itinerary_plan_ID = 2
      AND deleted = 0
      ORDER BY itinerary_route_ID
    `);

    console.log('All Routes for Plan 2:');
    routes.forEach((r, idx) => {
      console.log(`  Route ${idx+1} (ID ${r.itinerary_route_ID}): ${r.location_name} → ${r.next_visiting_location}`);
    });

    const route3 = routes[2]; // Third route
    console.log(`\n✓ Route 3 ID: ${route3.itinerary_route_ID}`);

    // Get all hotspot rows for Route 3
    const [rows] = await connection.execute(`
      SELECT 
        route_hotspot_ID,
        item_type,
        hotspot_order,
        hotspot_ID,
        hotspot_start_time,
        hotspot_end_time,
        hotspot_traveling_time
      FROM dvi_itinerary_route_hotspot_details
      WHERE itinerary_plan_ID = 2
      AND itinerary_route_ID = ?
      AND deleted = 0
      ORDER BY route_hotspot_ID
    `, [route3.itinerary_route_ID]);

    console.log(`\n✓ Found ${rows.length} rows\n`);

    // Group by hotspot_order to see the pairing
    const byOrder = {};
    rows.forEach(row => {
      if (!byOrder[row.hotspot_order]) {
        byOrder[row.hotspot_order] = [];
      }
      byOrder[row.hotspot_order].push(row);
    });

    // Get unique hotspot IDs
    const hotspotIDs = [...new Set(rows.filter(r => r.hotspot_ID).map(r => r.hotspot_ID))];
    console.log(`Hotspot IDs in order: [${hotspotIDs.join(', ')}]\n`);

    // Get details for each hotspot
    if (hotspotIDs.length > 0) {
      const [hotspots] = await connection.execute(`
        SELECT 
          hotspot_ID,
          hotspot_name,
          hotspot_location,
          hotspot_priority,
          hotspot_rating
        FROM dvi_hotspot_place
        WHERE hotspot_ID IN (${hotspotIDs.join(',')})
        ORDER BY FIELD(hotspot_ID, ${hotspotIDs.join(',')})
      `);

      console.log('Hotspot Details (in order they appear):');
      hotspots.forEach((h, idx) => {
        const locationCount = h.hotspot_location.split('|').length;
        console.log(`  ${idx+1}. Hotspot ${h.hotspot_ID}: ${h.hotspot_name}`);
        console.log(`     Priority: ${h.hotspot_priority}, Rating: ${h.hotspot_rating}, Locations: ${locationCount}`);
        console.log(`     Location: ${h.hotspot_location}`);
      });
    }

    console.log('\nRow-by-row breakdown:');
    Object.keys(byOrder).sort((a, b) => parseInt(a) - parseInt(b)).forEach(order => {
      console.log(`\nOrder ${order}:`);
      byOrder[order].forEach(row => {
        const itemTypes = {1: 'Refresh', 3: 'Travel', 4: 'Visit', 5: 'ToHotel', 6: 'AtHotel', 7: 'Return'};
        console.log(`  - ${itemTypes[row.item_type]} ${row.hotspot_ID ? `(Hotspot ${row.hotspot_ID})` : ''} [${row.hotspot_start_time} - ${row.hotspot_end_time}]`);
      });
    });

  } catch (error) {
    console.error('Error:', error);
  } finally {
    await connection.end();
  }
}

checkPlan2Route3();
