const http = require('http');

const planId = 33977;
const routeId = 207430;
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

console.log(`Triggering rebuild for Plan ${planId} via Route ${routeId}...`);
const req = http.request(options, (res) => {
  console.log(`STATUS: ${res.statusCode}`);
  res.setEncoding('utf8');
  res.on('data', (chunk) => {
    console.log(`BODY: ${chunk}`);
  });
});

req.on('error', (e) => {
  console.error(`problem with request: ${e.message}`);
});

req.write(body);
req.end();
