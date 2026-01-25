const http = require('http');

// First, let's get a fresh token by logging in
const loginBody = {
  "email": "admin@dvi.co.in",
  "password": "Admin@123" // You may need to adjust this
};

const loginData = JSON.stringify(loginBody);
const loginOptions = {
  hostname: '127.0.0.1',
  port: 4006,
  path: '/api/v1/auth/login',
  method: 'POST',
  headers: {
    'Content-Type': 'application/json',
    'Content-Length': Buffer.byteLength(loginData)
  }
};

console.log('\n=== AUTHENTICATING ===');
console.log('Logging in to get fresh token...\n');

const loginReq = http.request(loginOptions, (res) => {
  let data = '';
  
  res.on('data', (chunk) => {
    data += chunk;
  });
  
  res.on('end', () => {
    try {
      const response = JSON.parse(data);
      
      if (response.access_token) {
        console.log('✅ Authentication successful!');
        console.log(`Token: ${response.access_token}\n`);
        
        // Now use this token for hotel voucher cancellation
        testHotelVoucherCancellation(response.access_token);
      } else {
        console.log('❌ No token received');
        console.log(JSON.stringify(response, null, 2));
        process.exit(1);
      }
    } catch (e) {
      console.log('❌ Failed to parse login response:', data);
      process.exit(1);
    }
  });
});

loginReq.on('error', (error) => {
  console.error('❌ Login error:', error.message);
  process.exit(1);
});

loginReq.write(loginData);
loginReq.end();

// Function to test hotel voucher cancellation with the token
function testHotelVoucherCancellation(token) {
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

  console.log('=== TRIGGERING HOTEL VOUCHER CANCELLATION ===');
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
    console.log(`✅ Response Status: ${res.statusCode}\n`);
    
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
}
