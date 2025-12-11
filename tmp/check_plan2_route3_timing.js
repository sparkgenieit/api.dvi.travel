const mysql = require('mysql2/promise');

(async () => {
  const conn = await mysql.createConnection({
    host: 'localhost',
    user: 'root',
    password: 'my@Richlabz123',
    database: 'dvi_travels'
  });

  console.log('\n=== Plan 2 Route 429 (Route 3) Hotspot Timings ===\n');
  const [rows] = await conn.query(`
    SELECT 
      hotspot_order,
      hotspot_ID,
      item_type,
      TIME_FORMAT(hotspot_start_time, '%H:%i') as start,
      TIME_FORMAT(hotspot_end_time, '%H:%i') as end
    FROM dvi_itinerary_route_hotspot_details 
    WHERE itinerary_route_ID=429 AND hotspot_ID IN (16,18,25)
    ORDER BY hotspot_order
  `);
  
  console.table(rows);
  
  await conn.end();
})();
