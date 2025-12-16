const http = require('http');

const token = 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJzdWIiOiIxIiwiZW1haWwiOiJhZG1pbkBkdmkuY28uaW4iLCJyb2xlIjoxLCJpYXQiOjE3NjU2MDU2MTEsImV4cCI6MTc2NjIxMDQxMX0.DG8SZAtVZy-oqEbiixgbeXoHeqkCVY4wI4aVtvL7B3Q';

console.log('\n=== CHECKING DATABASE TABLES FOR HOTEL ASSIGNMENT ===\n');

const queries = [
  {
    name: 'dvi_itinerary_plan_hotel_details',
    query: `SELECT 
      itinerary_plan_hotel_details_ID,
      itinerary_plan_id,
      itinerary_route_id,
      itinerary_route_location,
      hotel_id,
      group_type,
      total_hotel_cost,
      deleted
    FROM dvi_itinerary_plan_hotel_details 
    WHERE itinerary_plan_id = (
      SELECT itinerary_plan_ID 
      FROM dvi_itinerary_plan_details 
      WHERE itinerary_quote_ID = 'DVI20251213'
    )
    ORDER BY group_type, itinerary_route_id`
  },
  {
    name: 'dvi_hotel (for hotel 610 - Mango Hill Shola Ooty)',
    query: `SELECT 
      hotel_id,
      hotel_name,
      hotel_location,
      hotel_category,
      deleted,
      status
    FROM dvi_hotel 
    WHERE hotel_id = 610`
  },
  {
    name: 'dvi_itinerary_route_details',
    query: `SELECT 
      itinerary_route_ID,
      itinerary_plan_ID,
      location_name,
      next_visiting_location,
      itinerary_route_date,
      no_of_days,
      deleted
    FROM dvi_itinerary_route_details 
    WHERE itinerary_plan_ID = (
      SELECT itinerary_plan_ID 
      FROM dvi_itinerary_plan_details 
      WHERE itinerary_quote_ID = 'DVI20251213'
    )
    ORDER BY no_of_days`
  }
];

function executeQuery(query) {
  return new Promise((resolve, reject) => {
    const postData = JSON.stringify({ query: query.query });

    const options = {
      hostname: '127.0.0.1',
      port: 4006,
      path: '/api/v1/debug/query',
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'Authorization': `Bearer ${token}`,
        'Content-Length': Buffer.byteLength(postData)
      }
    };

    const req = http.request(options, (res) => {
      let data = '';
      
      res.on('data', (chunk) => {
        data += chunk;
      });
      
      res.on('end', () => {
        try {
          const parsed = JSON.parse(data);
          resolve({ name: query.name, data: parsed });
        } catch (e) {
          resolve({ name: query.name, data: data });
        }
      });
    });

    req.on('error', (error) => {
      reject(error);
    });

    req.write(postData);
    req.end();
  });
}

async function runQueries() {
  for (const query of queries) {
    console.log(`\n${'='.repeat(80)}`);
    console.log(`TABLE: ${query.name}`);
    console.log('='.repeat(80));
    
    try {
      const result = await executeQuery(query);
      console.log(JSON.stringify(result.data, null, 2));
    } catch (err) {
      console.error('Error:', err.message);
    }
  }
  
  console.log('\n' + '='.repeat(80));
  console.log('ANALYSIS COMPLETE');
  console.log('='.repeat(80));
  console.log('\nKey Points to Check:');
  console.log('1. itinerary_route_location should match the destination location');
  console.log('2. hotel_id 610 (Mango Hill Shola Ooty) should NOT be assigned to Chennai');
  console.log('3. Hotels should match their hotel_location to route destination');
  console.log('\n');
}

runQueries().catch(console.error);
