const mysql = require('mysql2/promise');

(async () => {
  const c = await mysql.createConnection({
    host: 'localhost',
    user: 'root',
    password: 'my@Richlabz123',
    database: 'dvi_travels'
  });
  
  const [r] = await c.execute(
    'SELECT * FROM dvi_hotspot_timing WHERE hotspot_ID=18 AND hotspot_timing_day=1 AND deleted=0'
  );
  
  console.log('Hotspot 18 Tuesday (day 1) records:');
  r.forEach(t => {
    console.log(`  closed=${t.hotspot_closed}, start=${t.hotspot_start_time}, end=${t.hotspot_end_time}`);
  });
  
  await c.end();
})();
