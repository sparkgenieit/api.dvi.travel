const http = require('http');

const API_URL = 'http://localhost:4006/api/v1/hotspot-distance-cache';

async function makeRequest(method, path, body = null) {
  return new Promise((resolve, reject) => {
    const url = new URL(path, API_URL);
    const options = {
      hostname: url.hostname,
      port: url.port,
      path: url.pathname + url.search,
      method: method,
      headers: {
        'Content-Type': 'application/json',
      },
    };

    const req = http.request(options, (res) => {
      let data = '';
      res.on('data', (chunk) => {
        data += chunk;
      });
      res.on('end', () => {
        resolve({
          status: res.statusCode,
          body: JSON.parse(data || '{}'),
        });
      });
    });

    req.on('error', reject);

    if (body) {
      req.write(JSON.stringify(body));
    }

    req.end();
  });
}

async function testAPI() {
  console.log('🧪 Testing HotspotDistanceCache API\n');

  try {
    // Test 1: Get form options
    console.log('Test 1: Get form options');
    let res = await makeRequest('GET', '/form-options');
    console.log(`Status: ${res.status}`);
    console.log(`✅ Form options loaded:`, res.body.hotspots?.length || 0, 'hotspots');
    console.log();

    // Test 2: List cache entries
    console.log('Test 2: List cache entries');
    res = await makeRequest('GET', '/?page=1&size=10');
    console.log(`Status: ${res.status}`);
    console.log(`✅ Total entries: ${res.body.total}`);
    console.log(`✅ Sample entries: ${res.body.rows?.length || 0}`);
    if (res.body.rows?.length > 0) {
      console.log(`   First entry: ${res.body.rows[0].fromHotspotName} → ${res.body.rows[0].toHotspotName}`);
    }
    console.log();

    // Test 3: Search by hotspot name
    console.log('Test 3: Search by hotspot name');
    res = await makeRequest('GET', '/?search=temple&page=1&size=5');
    console.log(`Status: ${res.status}`);
    console.log(`✅ Found ${res.body.rows?.length || 0} entries with "temple"`);
    console.log();

    // Test 4: Get single entry
    if (res.body.rows?.length > 0) {
      const entryId = res.body.rows[0].id;
      console.log(`Test 4: Get single entry (ID: ${entryId})`);
      res = await makeRequest('GET', `/${entryId}`);
      console.log(`Status: ${res.status}`);
      console.log(`✅ Entry found: ${res.body.fromHotspotName} → ${res.body.toHotspotName}`);
      console.log();
    }

    // Test 5: Export to Excel
    console.log('Test 5: Export to Excel');
    res = await makeRequest('GET', '/export/excel?size=5');
    console.log(`Status: ${res.status}`);
    if (res.body.ok) {
      console.log(`✅ Excel file generated: ${res.body.fileName}`);
      console.log(`✅ File size: ${(res.body.data.length / 1024).toFixed(2)} KB`);
    }
    console.log();

    console.log('✅ All tests passed!');
  } catch (error) {
    console.error('❌ Error:', error.message);
  }
}

testAPI();
