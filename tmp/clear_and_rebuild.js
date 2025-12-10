const mysql = require('mysql2/promise');

async function clearAndRebuild() {
  const connection = await mysql.createConnection({
    host: 'localhost',
    user: 'root',
    password: 'my@Richlabz123',
    database: 'dvi_travels'
  });

  try {
    // Clear plan 4 data
    console.log('Deleting old plan 4 data...');
    const [result1] = await connection.execute(
      'DELETE FROM dvi_itinerary_route_hotspot_details WHERE itinerary_plan_ID = ?',
      [4]
    );
    console.log(`Deleted ${result1.affectedRows} rows`);

    // Verify deletion
    const [rows] = await connection.execute(
      'SELECT COUNT(*) as cnt FROM dvi_itinerary_route_hotspot_details WHERE itinerary_plan_ID = ?',
      [4]
    );
    console.log(`After delete: ${rows[0].cnt} rows`);

    await connection.end();

    // Now trigger rebuild via HTTP
    console.log('\nWaiting 2 seconds before triggering rebuild...');
    await new Promise(r => setTimeout(r, 2000));

    const http = require('http');
    const payload = {
      "plan": {
        "itinerary_plan_id": 4,
        "itinerary_plan_name": "Chennai to Pondicherry 2D1N",
        "itinerary_type_id": 1,
        "no_of_days": 2,
        "no_of_nights": 1,
        "travel_type_id": 2,
        "currency_id": 1,
        "agent_id": 1
      },
      "routes": [
        {
          "itinerary_route_id": 25,
          "route_name": "Chennai Intl to Chennai",
          "route_start_location_id": 1,
          "route_end_location_id": 2,
          "route_start_time": "10:30:00",
          "route_end_time": "11:45:00",
          "route_distance": 16,
          "route_duration": "00:45:00",
          "route_via_route_id": 16,
          "sequence": 1
        },
        {
          "itinerary_route_id": 26,
          "route_name": "Chennai to Pondicherry",
          "route_start_location_id": 2,
          "route_end_location_id": 5,
          "route_start_time": "13:00:00",
          "route_end_time": "16:30:00",
          "route_distance": 160,
          "route_duration": "03:30:00",
          "route_via_route_id": 17,
          "sequence": 2
        },
        {
          "itinerary_route_id": 27,
          "route_name": "Pondicherry to Pondicherry Airport",
          "route_start_location_id": 5,
          "route_end_location_id": 6,
          "route_start_time": "16:45:00",
          "route_end_time": "17:45:00",
          "route_distance": 12,
          "route_duration": "01:00:00",
          "route_via_route_id": 18,
          "sequence": 3
        }
      ],
      "vehicles": [
        {
          "vehicle_id": 5,
          "vehicle_type_id": 1,
          "vehicle_name": "Test Vehicle",
          "seating_capacity": 4
        }
      ],
      "travellers": [
        {
          "itinerary_traveller_id": 13,
          "traveller_name": "Test Traveller 1",
          "nationality_id": 1,
          "food_preference_id": 1,
          "age": 30,
          "gender": "M",
          "sequence": 1
        },
        {
          "itinerary_traveller_id": 14,
          "traveller_name": "Test Traveller 2",
          "nationality_id": 1,
          "food_preference_id": 1,
          "age": 28,
          "gender": "F",
          "sequence": 2
        }
      ]
    };

    const payloadStr = JSON.stringify(payload);
    const options = {
      hostname: 'localhost',
      port: 4006,
      path: '/api/v1/itineraries',
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'Content-Length': payloadStr.length
      }
    };

    console.log('\nSending rebuild request to /api/v1/itineraries...');
    
    return new Promise((resolve) => {
      const req = http.request(options, (res) => {
        let data = '';
        res.on('data', (chunk) => { data += chunk; });
        res.on('end', () => {
          console.log(`Response status: ${res.statusCode}`);
          if (res.statusCode === 201) {
            try {
              const body = JSON.parse(data);
              console.log('Success! Created plan:', body);
              resolve(true);
            } catch (e) {
              console.log('Response:', data.substring(0, 200));
              resolve(true);
            }
          } else {
            console.log('Error response:', data.substring(0, 300));
            resolve(false);
          }
        });
      });

      req.on('error', (e) => {
        console.error(`Request error: ${e.message}`);
        resolve(false);
      });

      req.write(payloadStr);
      req.end();
    });

  } catch (error) {
    console.error('Error:', error);
    await connection.end();
  }
}

clearAndRebuild();
