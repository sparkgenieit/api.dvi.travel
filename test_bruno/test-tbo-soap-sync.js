/**
 * TEST: TBO Soap Sync Service
 * 
 * Demonstrates how tbo-soap-sync.service.ts methods are called
 * via HTTP endpoints
 * 
 * Service Methods:
 * 1. syncCities() - Updates dvi_cities with TBO city codes
 * 2. syncHotelsForCity(tboCityCode) - Syncs hotels for a specific city
 * 3. syncAllCities() - Syncs all cities + their hotels (MAIN METHOD)
 * 4. getHotelCount() - Returns total hotel count in master database
 */

const http = require('http');

const token = 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJzdWIiOiIxIiwiZW1haWwiOiJhZG1pbkBkdmkuY28uaW4iLCJyb2xlIjoxLCJhZ2VudElkIjowLCJzdGFmZklkIjowLCJndWlkZUlkIjowLCJpYXQiOjE3NjcyNDkzMjAsImV4cCI6MTc2Nzg1NDEyMH0.0-AJW4SWm1NFTzJFjEAe69-byHfu0X1sFmGwP_fTOmw';

const BASE_URL = 'http://127.0.0.1:4006/api/v1';

// Test scenarios
const TESTS = [
  {
    name: '1. Get Current Hotel Count',
    method: 'GET',
    endpoint: '/hotels/sync/master-data/count',
    description: 'Calls: getHotelCount()'
  },
  {
    name: '2. Sync All Cities & Hotels',
    method: 'POST',
    endpoint: '/hotels/sync/all',
    description: 'Calls: syncAllCities() which calls syncCities() + syncHotelsForCity() for each city'
  },
  {
    name: '3. Sync Hotels for Specific City',
    method: 'POST',
    endpoint: '/hotels/sync/city/127343',
    description: 'Calls: syncHotelsForCity("127343") - Chennai'
  },
  {
    name: '4. Sync Hotels for Mahabalipuram',
    method: 'POST',
    endpoint: '/hotels/sync/city/126117',
    description: 'Calls: syncHotelsForCity("126117")'
  },
];

// Color codes
const colors = {
  reset: '\x1b[0m',
  bright: '\x1b[1m',
  blue: '\x1b[36m',
  green: '\x1b[32m',
  yellow: '\x1b[33m',
  red: '\x1b[31m',
};

function makeRequest(testIndex) {
  const test = TESTS[testIndex];
  
  return new Promise((resolve) => {
    const url = new URL(BASE_URL + test.endpoint);
    const options = {
      hostname: url.hostname,
      port: url.port,
      path: url.pathname + url.search,
      method: test.method,
      headers: {
        'Content-Type': 'application/json',
        'Authorization': `Bearer ${token}`,
      },
    };

    const req = http.request(options, (res) => {
      let data = '';
      res.on('data', (chunk) => {
        data += chunk;
      });
      res.on('end', () => {
        try {
          const parsed = JSON.parse(data);
          resolve({
            test,
            statusCode: res.statusCode,
            response: parsed,
            success: res.statusCode === 200 || res.statusCode === 201,
          });
        } catch (e) {
          resolve({
            test,
            statusCode: res.statusCode,
            response: data,
            success: false,
          });
        }
      });
    });

    req.on('error', (error) => {
      resolve({
        test,
        error: error.message,
        success: false,
      });
    });

    if (test.method === 'POST') {
      req.write(JSON.stringify({}));
    }
    req.end();
  });
}

async function runTests() {
  console.log(`\n${colors.bright}${colors.blue}╔══════════════════════════════════════════════════════╗${colors.reset}`);
  console.log(`${colors.bright}${colors.blue}║     TBO SOAP SYNC SERVICE - TEST FLOW                  ║${colors.reset}`);
  console.log(`${colors.bright}${colors.blue}╚══════════════════════════════════════════════════════╝${colors.reset}\n`);

  console.log(`${colors.yellow}📋 Service Location:${colors.reset}`);
  console.log(`   src/modules/hotels/services/tbo-soap-sync.service.ts\n`);

  console.log(`${colors.yellow}🔌 Controller Location:${colors.reset}`);
  console.log(`   src/modules/hotels/controllers/hotel-master-sync.controller.ts\n`);

  console.log(`${colors.yellow}📚 Available Methods in Service:${colors.reset}`);
  console.log(`   1. syncCities(countryCode?) - Update dvi_cities with TBO codes`);
  console.log(`   2. syncHotelsForCity(tboCityCode) - Sync hotels for one city`);
  console.log(`   3. syncAllCities() - Full sync (all cities + hotels)`);
  console.log(`   4. getHotelCount() - Get total hotel count\n`);

  console.log(`${colors.yellow}🎯 Test Scenarios:${colors.reset}\n`);

  for (let i = 0; i < TESTS.length; i++) {
    const test = TESTS[i];
    console.log(`${colors.bright}${test.name}${colors.reset}`);
    console.log(`   ${test.method} ${test.endpoint}`);
    console.log(`   Service Call: ${test.description}\n`);
  }

  console.log(`${colors.bright}${colors.green}▶ Running Tests...${colors.reset}\n`);
  console.log('─'.repeat(60) + '\n');

  // Run tests sequentially with delays
  for (let i = 0; i < TESTS.length; i++) {
    console.log(`\n${colors.bright}TEST ${i + 1}${colors.reset}`);
    const result = await makeRequest(i);

    if (result.error) {
      console.log(`${colors.red}❌ Error: ${result.error}${colors.reset}`);
    } else {
      console.log(`${result.success ? colors.green + '✅' : colors.red + '❌'} Status: ${result.statusCode}${colors.reset}`);
      console.log(`${colors.bright}Response:${colors.reset}`);
      console.log(JSON.stringify(result.response, null, 2));
    }

    // Wait before next test
    if (i < TESTS.length - 1) {
      await new Promise((resolve) => setTimeout(resolve, 1000));
    }
  }

  console.log('\n' + '─'.repeat(60));
  console.log(`\n${colors.bright}${colors.green}✅ All tests completed!${colors.reset}\n`);

  // Show database summary
  console.log(`${colors.yellow}📊 Database Sync Summary:${colors.reset}`);
  console.log(`   Tables Updated:`);
  console.log(`   • dvi_cities - Gets tbo_city_code populated`);
  console.log(`   • tbo_hotel_master - Gets hotel codes for each city\n`);

  console.log(`${colors.yellow}🌍 Cities Synced (from TBO_CITIES mapping):${colors.reset}`);
  const cities = [
    { code: '418069', name: 'Delhi' },
    { code: '144306', name: 'Mumbai' },
    { code: '111124', name: 'Bangalore' },
    { code: '100589', name: 'Agra' },
    { code: '145710', name: 'Hyderabad' },
    { code: '122175', name: 'Jaipur' },
    { code: '113128', name: 'Kolkata' },
    { code: '127343', name: 'Chennai' },
    { code: '126117', name: 'Mahabalipuram' },
    { code: '139605', name: 'Thanjavur' },
    { code: '127067', name: 'Madurai' },
    { code: '133179', name: 'Rameswaram' },
  ];

  cities.forEach((city) => {
    console.log(`   • ${city.name} (${city.code})`);
  });

  console.log(`\n${colors.bright}${colors.blue}═══════════════════════════════════════════════════════${colors.reset}\n`);
}

// Run all tests
runTests().catch(console.error);
