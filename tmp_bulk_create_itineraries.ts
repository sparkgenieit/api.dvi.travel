
import * as mysql from 'mysql2/promise';
import * as http from 'http';

const dbConfig = {
  host: 'localhost',
  user: 'root',
  password: 'my@Richlabz123',
  database: 'dvi_api'
};

const token = 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJzdWIiOiIxIiwiZW1haWwiOiJhZG1pbkBkdmkuY28uaW4iLCJyb2xlIjoxLCJpYXQiOjE3NjU5MTk5MzIsImV4cCI6MTc2NjUyNDczMn0.wRUwsJ0XwXMYt8ATUuh_YSHI6wfjoPbM1yo4TceCqY8';

async function postItinerary(payload: any) {
  return new Promise((resolve, reject) => {
    const postData = JSON.stringify(payload);
    const options = {
      hostname: '127.0.0.1',
      port: 4006,
      path: '/api/v1/itineraries',
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'Authorization': `Bearer ${token}`,
        'Content-Length': Buffer.byteLength(postData)
      }
    };

    const req = http.request(options, (res) => {
      let data = '';
      res.on('data', (chunk) => data += chunk);
      res.on('end', () => {
        if (res.statusCode && res.statusCode >= 200 && res.statusCode < 300) {
          try {
            resolve(JSON.parse(data));
          } catch (e) {
            resolve(data);
          }
        } else {
          reject(new Error(`Status ${res.statusCode}: ${data}`));
        }
      });
    });

    req.on('error', (e) => reject(e));
    req.write(postData);
    req.end();
  });
}

async function main() {
  let connection;
  try {
    connection = await mysql.createConnection(dbConfig);
    console.log('Connected to dvi_api');

    // Fetch 10 random plans from dvi_api
    const [plans]: any = await connection.execute(`
      SELECT * FROM dvi_itinerary_plan_details 
      WHERE arrival_location IS NOT NULL AND departure_location IS NOT NULL
      ORDER BY RAND() LIMIT 10
    `);

    console.log(`Found ${plans.length} plans to recreate.`);

    for (const p of plans) {
      console.log(`\nProcessing Plan ID ${p.itinerary_plan_ID} from dvi_api...`);

      // Fetch routes for this plan
      const [routes]: any = await connection.execute(
        'SELECT * FROM dvi_itinerary_route_details WHERE itinerary_plan_ID = ? ORDER BY itinerary_route_ID ASC',
        [p.itinerary_plan_ID]
      );

      if (routes.length === 0) {
        console.log(`  Skipping: No routes found for plan ${p.itinerary_plan_ID}`);
        continue;
      }

      // Build payload
      const payload = {
        plan: {
          agent_id: 283, // Using default from trigger_optimization.js
          staff_id: 0,
          location_id: 0,
          arrival_point: p.arrival_location || "Bangalore",
          departure_point: p.departure_location || "Chennai",
          itinerary_preference: 3,
          itinerary_type: 2,
          preferred_hotel_category: [2], // Defaulting to 2
          hotel_facilities: ["24hr-business-center"],
          trip_start_date: "2026-01-10T08:00:00+05:30",
          trip_end_date: "2026-01-15T19:00:00+05:30",
          pick_up_date_and_time: "2026-01-10T12:00:00+05:30",
          arrival_type: 1,
          departure_type: 1,
          no_of_nights: routes.length - 1,
          no_of_days: routes.length,
          budget: 25000,
          entry_ticket_required: 0,
          guide_for_itinerary: 0,
          nationality: 101,
          food_type: 0,
          adult_count: 2,
          child_count: 0,
          infant_count: 0,
          special_instructions: `Recreated from dvi_api Plan ${p.itinerary_plan_ID}`
        },
        routes: routes.map((r: any, idx: number) => {
          const date = new Date("2026-01-10");
          date.setDate(date.getDate() + idx);
          return {
            location_name: r.location_name || "",
            next_visiting_location: r.next_visiting_location || "",
            itinerary_route_date: date.toISOString(),
            no_of_days: idx + 1,
            no_of_km: "",
            direct_to_next_visiting_place: r.direct_to_next_visiting_place || 0,
            via_route: "",
            via_routes: []
          };
        }),
        vehicles: [{ vehicle_type_id: 25, vehicle_count: 1 }],
        travellers: [
          { room_id: 1, traveller_type: 1 },
          { room_id: 1, traveller_type: 1 }
        ]
      };

      try {
        const result: any = await postItinerary(payload);
        console.log(`  ✅ Created Plan: ${result?.plan?.itinerary_plan_ID || 'Success'}`);
      } catch (err: any) {
        console.error(`  ❌ Failed: ${err.message}`);
      }
    }

  } catch (error) {
    console.error('Error:', error);
  } finally {
    if (connection) await connection.end();
  }
}

main();
