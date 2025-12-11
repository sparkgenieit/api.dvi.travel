const mysql = require('mysql2/promise');
require('dotenv').config();

async function main() {
  const conn = await mysql.createConnection(process.env.DATABASE_URL);
  
  console.log('\n=== PARKING CHARGES CHECK ===\n');
  
  // Check if plan 5 has parking charge records
  const [p5Parking] = await conn.query(
    'SELECT COUNT(*) as count, SUM(parking_charges_amt) as total FROM dvi_itinerary_route_hotspot_parking_charge WHERE itinerary_plan_ID = 5'
  );
  
  // Check plan 2 for comparison
  const [p2Parking] = await conn.query(
    'SELECT COUNT(*) as count, SUM(parking_charges_amt) as total FROM dvi_itinerary_route_hotspot_parking_charge WHERE itinerary_plan_ID = 2'
  );
  
  console.log('Plan 2 Parking Charges:');
  console.log(`  Records: ${p2Parking[0].count}`);
  console.log(`  Total: ${p2Parking[0].total}`);
  
  console.log('\nPlan 5 Parking Charges:');
  console.log(`  Records: ${p5Parking[0].count}`);
  console.log(`  Total: ${p5Parking[0].total}`);
  
  // Check vehicle details total_parking_charges
  const [p2Vehicle] = await conn.query(
    'SELECT total_parking_charges FROM dvi_itinerary_plan_vehicle_details WHERE itinerary_plan_ID = 2 LIMIT 1'
  );
  
  const [p5Vehicle] = await conn.query(
    'SELECT total_parking_charges FROM dvi_itinerary_plan_vehicle_details WHERE itinerary_plan_ID = 5 LIMIT 1'
  );
  
  console.log('\n=== VEHICLE DETAILS PARKING ===');
  console.log(`Plan 2: ${p2Vehicle[0]?.total_parking_charges || 0}`);
  console.log(`Plan 5: ${p5Vehicle[0]?.total_parking_charges || 0}`);
  
  console.log('\n=== HOTEL DATA CHECK ===');
  
  // Check hotel details
  const [p2Hotel] = await conn.query(
    'SELECT COUNT(*) as total, SUM(hotel_id > 0) as with_hotel FROM dvi_itinerary_plan_hotel_room_details WHERE itinerary_plan_id = 2'
  );
  
  const [p5Hotel] = await conn.query(
    'SELECT COUNT(*) as total, SUM(hotel_id > 0) as with_hotel FROM dvi_itinerary_plan_hotel_room_details WHERE itinerary_plan_id = 5'
  );
  
  console.log('\nPlan 2 Hotel Rooms:');
  console.log(`  Total records: ${p2Hotel[0].total}`);
  console.log(`  With hotel_id > 0: ${p2Hotel[0].with_hotel}`);
  
  console.log('\nPlan 5 Hotel Rooms:');
  console.log(`  Total records: ${p5Hotel[0].total}`);
  console.log(`  With hotel_id > 0: ${p5Hotel[0].with_hotel}`);
  
  await conn.end();
}

main().catch(console.error);
