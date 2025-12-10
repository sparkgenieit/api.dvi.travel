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

const options = {
  hostname: 'localhost',
  port: 4006,
  path: '/api/v1/itineraries',
  method: 'POST',
  headers: {
    'Content-Type': 'application/json',
    'Content-Length': JSON.stringify(payload).length
  }
};

const req = http.request(options, (res) => {
  console.log(`STATUS: ${res.statusCode}`);
  let data = '';
  res.on('data', (chunk) => { data += chunk; });
  res.on('end', () => {
    console.log('RESPONSE:', data);
    process.exit(0);
  });
});

req.on('error', (e) => {
  console.error(`ERROR: ${e.message}`);
  process.exit(1);
});

req.write(JSON.stringify(payload));
req.end();

console.log('Sending rebuild request to /api/v1/itineraries...');
