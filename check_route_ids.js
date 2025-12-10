const mysql = require('mysql2/promise');

(async () => {
  const db = await mysql.createConnection({
    host: 'localhost',
    user: 'root',
    password: '',
    database: 'dvi_db'
  });

  const [routes] = await db.query(`
    SELECT itinerary_route_ID, itinerary_route_date 
    FROM itinerary_route_master 
    WHERE itinerary_plan_ID = 5 
    ORDER BY itinerary_route_date
  `);

  console.log('=== ROUTE IDs FOR PLAN 5 ===');
  routes.forEach((r, i) => {
    const date = r.itinerary_route_date.toISOString().split('T')[0];
    console.log(`Route ${i + 1} (date ${date}): ID ${r.itinerary_route_ID}`);
  });

  await db.end();
})();
