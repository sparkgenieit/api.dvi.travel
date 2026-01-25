const http = require('http');

const token = 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJzdWIiOiIxIiwiZW1haWwiOiJhZG1pbkBkdmkuY28uaW4iLCJyb2xlIjoxLCJhZ2VudElkIjowLCJzdGFmZklkIjowLCJndWlkZUlkIjowLCJpYXQiOjE3Njg4ODIwMTUsImV4cCI6MTc2OTQ4NjgxNX0.nEg5HBZiHpDDQ-fwwANJzUT1kjaVl9ZHN5ejKNnagCI';

const requestBody = {
  "itineraryPlanId": 11,
  "vouchers": [
    {
      "hotelId": 1219121,
      "hotelDetailsIds": [385],
      "routeDates": ["2026-04-27"],
      "confirmedBy": "testtt",
      "emailId": "kiran.phpfish@gmail.com",
      "mobileNumber": "4234234",
      "status": "cancelled",
      "invoiceTo": "gst_bill_against_dvi",
      "voucherTermsCondition": "Standard hotel voucher terms and conditions apply."
    }
  ]
};

console.log('\n=== TRIGGERING HOTEL VOUCHER CANCELLATION ===');
console.log('Sending POST request to http://127.0.0.1:4006/api/v1/itineraries/11/hotel-vouchers\n');

const postData = JSON.stringify(requestBody);
const options = {
  hostname: '127.0.0.1',
  port: 4006,
  path: '/api/v1/itineraries/11/hotel-vouchers',
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
    console.log('\n✅ Done! Hotel voucher cancellation request completed');
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
