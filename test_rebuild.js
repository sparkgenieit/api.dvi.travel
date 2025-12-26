const http = require('http');

const planId = 33977;
const routeId = 207430;

// First, let's try a simpler endpoint to see if the server is working
const testOptions = {
  hostname: '127.0.0.1',
  port: 4006,
  path: `/api/v1/health`,
  method: 'GET',
  headers: {
    'Content-Type': 'application/json',
  }
};

console.log(`Testing server health...`);
const testReq = http.request(testOptions, (res) => {
  console.log(`Health check STATUS: ${res.statusCode}`);
  res.setEncoding('utf8');
  let data = '';
  res.on('data', (chunk) => {
    data += chunk;
  });
  res.on('end', () => {
    console.log(`Health response: ${data}`);
    
    // Now try the actual rebuild
    console.log(`\nTrigger rebuild for Plan ${planId} via Route ${routeId}...`);
    const options = {
      hostname: '127.0.0.1',
      port: 4006,
      path: `/api/v1/itineraries/${planId}/route/${routeId}/times`,
      method: 'PATCH',
      headers: {
        'Content-Type': 'application/json',
        'Authorization': 'Bearer eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJzdWIiOiIxIiwiZW1haWwiOiJhZG1pbkBkdmkuY28uaW4iLCJyb2xlIjoxLCJhZ2VudElkIjowLCJzdGFmZklkIjowLCJndWlkZUlkIjowLCJpYXQiOjE3NjY1ODA1MDEsImV4cCI6MTc2NzE4NTMwMX0.qUSoZDU_LRQf9-dPm7HW-5t7zLIcfGFDjsWc-D1NKSM'
      }
    };

    const body = JSON.stringify({
      startTime: '09:00:00',
      endTime: '20:00:00'
    });

    const req = http.request(options, (res) => {
      console.log(`Rebuild STATUS: ${res.statusCode}`);
      res.setEncoding('utf8');
      let respData = '';
      res.on('data', (chunk) => {
        respData += chunk;
      });
      res.on('end', () => {
        console.log(`Response: ${respData.substring(0, 500)}`);
      });
    });

    req.on('error', (e) => {
      console.error(`Request error: ${e.message}`);
    });

    req.write(body);
    req.end();
  });
});

testReq.on('error', (e) => {
  console.error(`Health check error: ${e.message}`);
});

testReq.end();
