const { PrismaClient } = require('@prisma/client');
const http = require('http');

const prisma = new PrismaClient();

async function clearPlan4() {
  try {
    console.log('Clearing plan 4 data...');
    const result = await prisma.$executeRaw`
      DELETE FROM dvi_itinerary_route_hotspot_details 
      WHERE itinerary_plan_ID = 4
    `;
    console.log('Deleted ' + result + ' rows from dvi_itinerary_route_hotspot_details');
  } catch (err) {
    console.error('Error clearing plan 4:', err.message);
    throw err;
  } finally {
    await prisma.$disconnect();
  }
}

function rebuildPlan4() {
  return new Promise((resolve, reject) => {
    const payload = {
        "plan": {
            "itinerary_plan_id": 4,
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
            "trip_start_date": "2025-12-10T11:00:00+05:30",
            "trip_end_date": "2025-12-12T12:00:00+05:30",
            "pick_up_date_and_time": "2025-12-10T11:00:00+05:30",
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
                "itinerary_route_date": "2025-12-10T00:00:00+05:30",
                "no_of_days": 1,
                "no_of_km": "",
                "direct_to_next_visiting_place": 1,
                "via_route": ""
            },
            {
                "location_name": "Chennai",
                "next_visiting_location": "Pondicherry",
                "itinerary_route_date": "2025-12-11T00:00:00+05:30",
                "no_of_days": 2,
                "no_of_km": "",
                "direct_to_next_visiting_place": 1,
                "via_route": ""
            },
            {
                "location_name": "Pondicherry",
                "next_visiting_location": "Pondicherry Airport",
                "itinerary_route_date": "2025-12-12T00:00:00+05:30",
                "no_of_days": 3,
                "no_of_km": "",
                "direct_to_next_visiting_place": 1,
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

    const postData = JSON.stringify(payload);

    const options = {
      hostname: '127.0.0.1',
      port: 4006,
      path: '/api/v1/itineraries',
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'Content-Length': postData.length,
      },
    };

    const req = http.request(options, (res) => {
      let data = '';
      res.on('data', (chunk) => {
        data += chunk;
      });
      res.on('end', () => {
        console.log('Rebuild Response Status:', res.statusCode);
        if (res.statusCode >= 200 && res.statusCode < 300) {
          console.log('Plan 4 rebuild triggered successfully');
          resolve(true);
        } else {
          console.log('Error Response:', data);
          reject(new Error('HTTP ' + res.statusCode));
        }
      });
    });

    req.on('error', reject);
    req.write(postData);
    req.end();
  });
}

async function main() {
  try {
    await clearPlan4();
    console.log('\nWaiting 1 second before rebuild...');
    await new Promise(resolve => setTimeout(resolve, 1000));
    
    console.log('\nTriggering rebuild...');
    await rebuildPlan4();
    
    console.log('\nWaiting 2 seconds for rebuild to complete...');
    await new Promise(resolve => setTimeout(resolve, 2000));
    
    console.log('\nRebuild cycle complete!');
    process.exit(0);
  } catch (err) {
    console.error('Fatal error:', err.message);
    process.exit(1);
  }
}

main();
