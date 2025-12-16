const http = require('http');

const token = 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJzdWIiOiIxIiwiZW1haWwiOiJhZG1pbkBkdmkuY28uaW4iLCJyb2xlIjoxLCJpYXQiOjE3NjU2MDU2MTEsImV4cCI6MTc2NjIxMDQxMX0.DG8SZAtVZy-oqEbiixgbeXoHeqkCVY4wI4aVtvL7B3Q';

// Test cases with different route combinations
const testCases = [
  {
    name: 'ECR Beach to Munnar',
    params: {
      DAY_NO: '1',
      selected_source_location: 'ECR Beach, Chennai, Tamil Nadu',
      selected_next_visiting_location: 'Munnar',
      itinerary_route_date: '16-12-2025',
      itinerary_session_id: 'TEST123',
      itinerary_plan_ID: '1'
    }
  },
  {
    name: 'Chennai to Bangalore',
    params: {
      DAY_NO: '1',
      selected_source_location: 'Chennai',
      selected_next_visiting_location: 'Bangalore',
      itinerary_route_date: '16-12-2025',
      itinerary_session_id: 'TEST123',
      itinerary_plan_ID: '1'
    }
  },
  {
    name: 'Mumbai to Goa',
    params: {
      DAY_NO: '1',
      selected_source_location: 'Mumbai',
      selected_next_visiting_location: 'Goa',
      itinerary_route_date: '16-12-2025',
      itinerary_session_id: 'TEST123',
      itinerary_plan_ID: '1'
    }
  }
];

function makeRequest(testCase) {
  return new Promise((resolve, reject) => {
    const params = new URLSearchParams(testCase.params).toString();
    const path = `/api/v1/itinerary-via-routes/form?${params}`;

    const options = {
      hostname: '127.0.0.1',
      port: 4006,
      path: path,
      method: 'GET',
      headers: {
        'Content-Type': 'application/json',
        'Authorization': `Bearer ${token}`
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
          resolve({ status: res.statusCode, data: parsed });
        } catch (e) {
          resolve({ status: res.statusCode, data: data });
        }
      });
    });

    req.on('error', (error) => {
      reject(error);
    });

    req.end();
  });
}

async function testViaRoutes() {
  console.log('='.repeat(80));
  console.log('VIA ROUTES API TEST SCRIPT');
  console.log('='.repeat(80));
  console.log('\n');

  for (const testCase of testCases) {
    console.log(`\n${'='.repeat(80)}`);
    console.log(`TEST CASE: ${testCase.name}`);
    console.log('='.repeat(80));
    console.log('Parameters:');
    console.log(JSON.stringify(testCase.params, null, 2));
    console.log('\n');

    try {
      const response = await makeRequest(testCase);
      
      console.log('✅ SUCCESS');
      console.log('Response Status:', response.status);
      console.log('Response Data:');
      console.log(JSON.stringify(response.data, null, 2));
      
      // Analysis
      if (response.data && response.data.data) {
        const { existing, options } = response.data.data;
        console.log('\n📊 ANALYSIS:');
        console.log(`  - Existing via routes: ${existing?.length || 0}`);
        console.log(`  - Available options: ${options?.length || 0}`);
        
        if (!options || options.length === 0) {
          console.log('  ⚠️  NO VIA ROUTES FOUND!');
          console.log('  Possible reasons:');
          console.log('    1. Route pair does not exist in dvi_stored_locations');
          console.log('    2. Location names do not match exactly (case-sensitive)');
          console.log('    3. No via routes configured for this route in dvi_stored_location_via_routes');
        } else {
          console.log('  ✅ Via routes found successfully!');
          console.log(`  Options: ${options.map(o => o.label || o.via_route_location).join(', ')}`);
        }
      }

    } catch (error) {
      console.log('❌ ERROR');
      console.log('Error Message:', error.message);
    }

    console.log('\n');
  }

  console.log('='.repeat(80));
  console.log('TEST SCRIPT COMPLETED');
  console.log('='.repeat(80));
  console.log('\n');
  console.log('💡 TIP: Check the backend console for detailed debug logs');
  console.log('Look for messages like:');
  console.log('  - "VIA ROUTE FORM REQUEST"');
  console.log('  - "FOUND BASE LOCATION"');
  console.log('  - "FETCHING VIA ROUTES FOR location_id"');
  console.log('  - "VIA ROUTE OPTIONS FOUND"');
  console.log('\n');
}

// Run the tests
testViaRoutes().catch(console.error);
