const mysql = require('mysql2/promise');

async function checkPHPRoute1() {
  const connection = await mysql.createConnection({
    host: 'localhost',
    user: 'root',
    password: 'my@Richlabz123',
    database: 'dvi_travels'
  });

  try {
    // Get PHP Plan 2 Route 1 (Chennai -> Chennai)
    const [routes] = await connection.execute(`
      SELECT * FROM routes 
      WHERE plan_id = 2 
      ORDER BY route_order ASC
    `);

    console.log('\n=== PHP PLAN 2 ROUTE STRUCTURE ===\n');
    routes.forEach((r, i) => {
      console.log(`Route ${i+1}: ${r.visiting_place} → ${r.next_visiting_place}`);
    });

    // Get Route 1 details
    const route1 = routes[0];
    console.log(`\n=== ROUTE 1 (ID ${route1.route_id}): ${route1.visiting_place} → ${route1.next_visiting_place} ===\n`);

    const [timeline] = await connection.execute(`
      SELECT * FROM itinerary_timeline 
      WHERE route_id = ? 
      ORDER BY timeline_order ASC
    `, [route1.route_id]);

    console.log('Timeline items:');
    timeline.forEach(item => {
      if (item.item_type === 4) {
        console.log(`  Hotspot ${item.hotspot_id}: Order ${item.timeline_order}`);
      } else if (item.item_type === 2) {
        console.log(`  Refreshment: Order ${item.timeline_order}`);
      }
    });

    // Get hotspot IDs
    const hotspotIds = timeline.filter(t => t.item_type === 4).map(t => t.hotspot_id);
    console.log(`\nRoute 1 Hotspot IDs: ${hotspotIds.join(', ')}`);

    // Check if hotspot 4 is in Route 1
    if (hotspotIds.includes(4)) {
      console.log('\n✅ HOTSPOT 4 IS IN PHP ROUTE 1 (Chennai → Chennai)');
    } else {
      console.log('\n❌ HOTSPOT 4 IS NOT IN PHP ROUTE 1');
    }

    // Get Route 2 details
    const route2 = routes[1];
    console.log(`\n=== ROUTE 2 (ID ${route2.route_id}): ${route2.visiting_place} → ${route2.next_visiting_place} ===\n`);

    const [timeline2] = await connection.execute(`
      SELECT * FROM itinerary_timeline 
      WHERE route_id = ? 
      ORDER BY timeline_order ASC
    `, [route2.route_id]);

    const hotspotIds2 = timeline2.filter(t => t.item_type === 4).map(t => t.hotspot_id);
    console.log(`Route 2 Hotspot IDs: ${hotspotIds2.join(', ')}`);

    if (hotspotIds2.includes(4)) {
      console.log('\n✅ HOTSPOT 4 IS IN PHP ROUTE 2 (Chennai → Pondicherry)');
    } else {
      console.log('\n❌ HOTSPOT 4 IS NOT IN PHP ROUTE 2');
    }

    console.log('\n=== ANALYSIS ===\n');
    console.log('If hotspot 4 is in BOTH Route 1 and Route 2 in PHP,');
    console.log('then PHP allows hotspot reuse across routes.');
    console.log('NestJS currently blocks hotspot reuse (duplicate prevention).');

  } finally {
    await connection.end();
  }
}

checkPHPRoute1().catch(console.error);
