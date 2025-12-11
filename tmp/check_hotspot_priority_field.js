const mysql = require('mysql2/promise');

async function checkPriorityField() {
  const conn = await mysql.createConnection({
    host: 'localhost',
    user: 'root',
    password: 'my@Richlabz123',
    database: 'dvi_travels'
  });

  console.log('\n=== CHECKING HOTSPOT_PRIORITY FIELD ===\n');

  const [rows] = await conn.execute(`
    SELECT 
      hotspot_ID,
      hotspot_name,
      hotspot_location,
      hotspot_priority,
      hotspot_rating
    FROM dvi_hotspot_place
    WHERE hotspot_ID IN (16, 18, 25)
    ORDER BY hotspot_ID
  `);

  console.table(rows);

  await conn.end();
}

checkPriorityField().catch(console.error);
