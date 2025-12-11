const http = require('http');

const token = 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJzdWIiOiIxIiwiZW1haWwiOiJhZG1pbkBkdmkuY28uaW4iLCJyb2xlIjoxLCJpYXQiOjE3NjQ5OTg3ODIsImV4cCI6MTc2NTYwMzU4Mn0.UFtOqNeZo9JWD2loHDI8WDTr_kEVPR1v1jJwSXY0FUY';

const requestBody = {
    "plan": {
        "itinerary_plan_id": 5,
        "agent_id": 126,
        "staff_id": 0,
        "location_id": 0,
        "arrival_point": "Chennai International Airport",
        "departure_point": "Pondicherry Airport",
        "itinerary_preference": 3,
        "itinerary_type": 2,
        "preferred_hotel_category": [
            2
        ],
        "hotel_facilities": [],
        "trip_start_date": "2025-12-13T11:00:00+05:30",
        "trip_end_date": "2025-12-15T20:57:00+05:30",
        "pick_up_date_and_time": "2025-12-13T11:00:00+05:30",
        "arrival_type": 1,
        "departure_type": 1,
        "no_of_nights": 2,
        "no_of_days": 3,
        "budget": 15000,
        "entry_ticket_required": 0,
        "guide_for_itinerary": 0,
        "nationality": 101,
        "food_type": 0,
        "adult_count": 2,
        "child_count": 0,
        "infant_count": 0,
        "special_instructions": ""
    },
    "routes": [
        {
            "location_name": "Chennai International Airport",
            "next_visiting_location": "Chennai",
            "itinerary_route_date": "2025-12-13T00:00:00+05:30",
            "no_of_days": 1,
            "no_of_km": "",
            "direct_to_next_visiting_place": 0,
            "via_route": ""
        },
        {
            "location_name": "Chennai",
            "next_visiting_location": "Pondicherry",
            "itinerary_route_date": "2025-12-14T00:00:00+05:30",
            "no_of_days": 2,
            "no_of_km": "",
            "direct_to_next_visiting_place": 0,
            "via_route": ""
        },
        {
            "location_name": "Pondicherry",
            "next_visiting_location": "Pondicherry Airport",
            "itinerary_route_date": "2025-12-15T00:00:00+05:30",
            "no_of_days": 3,
            "no_of_km": "",
            "direct_to_next_visiting_place": 0,
            "via_route": ""
        }
    ],
    "vehicles": [
        {
            "vehicle_type_id": 1,
            "vehicle_count": 1
        }
    ],
    "travellers": [
        {
            "room_id": 1,
            "traveller_type": 1
        },
        {
            "room_id": 1,
            "traveller_type": 1
        }
    ]
};

console.log('\n=== TRIGGERING PLAN 5 OPTIMIZATION ===');
console.log('Sending POST request to /api/v1/itineraries\n');

const postData = JSON.stringify(requestBody);

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
  console.log(`\n✅ Response Status: ${res.statusCode}\n`);
  
  let data = '';
  res.on('data', (chunk) => {
    data += chunk;
  });
  
  res.on('end', () => {
    console.log('=== RESPONSE ===');
    try {
      const parsed = JSON.parse(data);
      console.log(JSON.stringify(parsed, null, 2));
    } catch (e) {
      console.log(data);
    }
    console.log('\n✅ Done! Log file should be in tmp/ directory');
    console.log('Run: node read_latest_log.js\n');
    process.exit(0);
  });
});

req.on('error', (error) => {
  console.error('❌ Error:', error.message);
  process.exit(1);
});

console.log('Writing request body...');
req.write(postData);
req.end();
console.log('Request sent! Waiting for response...\n');
