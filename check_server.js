const http = require('http');

const options = {
  hostname: '127.0.0.1',
  port: 4006,
  path: '/api/v1/itineraries/5/test',
  method: 'GET'
};

console.log('Testing server connection...');

const req = http.request(options, (res) => {
  console.log(`✅ Server is running! Status: ${res.statusCode}`);
  process.exit(0);
});

req.on('error', (error) => {
  console.error('❌ Server not running:', error.message);
  console.log('\nPlease start the server in another terminal with: npm run start:dev');
  process.exit(1);
});

req.end();
