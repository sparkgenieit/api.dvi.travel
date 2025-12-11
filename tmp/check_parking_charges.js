const mysql = require('mysql2/promise');
require('dotenv').config();

async function checkParkingCharges() {
  const conn = await mysql.createConnection(process.env.DATABASE_URL);
  
  console.log('\n=== PARKING CHARGES CHECK ===\n');
  
  // Check if parking charges exist for plan 5
  const [charges5] = await conn.query(`
    SELECT COUNT(*) as count 
    FROM dvi_itinerary_route_hotspot_parking_charge 
    WHERE itinerary_plan_id = 5
  `);
  
  console.log('Plan 5 parking charge records:', charges5[0].count);
  
  // Check plan 2
  const [charges2] = await conn.query(`
    SELECT COUNT(*) as count 
    FROM dvi_itinerary_route_hotspot_parking_charge 
    WHERE itinerary_plan_id = 2
  `);
  
  console.log('Plan 2 parking charge records:', charges2[0].count);
  
  // Show sample from plan 2
  if (charges2[0].count > 0) {
    const [sample] = await conn.query(`
      SELECT * 
      FROM dvi_itinerary_route_hotspot_parking_charge 
      WHERE itinerary_plan_id = 2
      LIMIT 3
    `);
    
    console.log('\nPlan 2 sample parking charges:');
    sample.forEach((row, i) => {
      console.log(`  ${i+1}. Route ${row.itinerary_route_id}, Vehicle ${row.vehicle_type_id}: ₹${row.parking_charge}`);
    });
  }
  
  // Check vehicle details for parking charges
  console.log('\n=== VEHICLE DETAILS PARKING CHARGES ===\n');
  
  const [veh2] = await conn.query(`
    SELECT 
      vehicle_parking_charges,
      vehicle_toll_charges,
      vehicle_permit_charges,
      vehicle_base_fare
    FROM dvi_itinerary_plan_vehicle_details 
    WHERE itinerary_plan_ID = 2
  `);
  
  const [veh5] = await conn.query(`
    SELECT 
      vehicle_parking_charges,
      vehicle_toll_charges,
      vehicle_permit_charges,
      vehicle_base_fare
    FROM dvi_itinerary_plan_vehicle_details 
    WHERE itinerary_plan_ID = 5
  `);
  
  console.log('Plan 2 Vehicle Details:');
  console.log('  Base Fare:', veh2[0]?.vehicle_base_fare);
  console.log('  Parking:', veh2[0]?.vehicle_parking_charges);
  console.log('  Toll:', veh2[0]?.vehicle_toll_charges);
  console.log('  Permit:', veh2[0]?.vehicle_permit_charges);
  
  console.log('\nPlan 5 Vehicle Details:');
  console.log('  Base Fare:', veh5[0]?.vehicle_base_fare);
  console.log('  Parking:', veh5[0]?.vehicle_parking_charges);
  console.log('  Toll:', veh5[0]?.vehicle_toll_charges);
  console.log('  Permit:', veh5[0]?.vehicle_permit_charges);
  
  await conn.end();
}

checkParkingCharges().catch(console.error);
