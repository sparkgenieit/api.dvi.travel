const mysql = require('mysql2/promise');

(async () => { 
  const c = await mysql.createConnection({
    host:'localhost',
    user:'root',
    password:'my@Richlabz123',
    database:'dvi_travels'
  }); 
  
  const [cols] = await c.execute('SHOW COLUMNS FROM dvi_hotspot_timing'); 
  console.log('dvi_hotspot_timing columns:'); 
  cols.forEach(col => console.log(`  - ${col.Field}`)); 
  
  await c.end(); 
})()
