const http = require('http');

const token = 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJzdWIiOiIxIiwiZW1haWwiOiJhZG1pbkBkdmkuY28uaW4iLCJyb2xlIjoxLCJpYXQiOjE3NjU2MDU2MTEsImV4cCI6MTc2NjIxMDQxMX0.DG8SZAtVZy-oqEbiixgbeXoHeqkCVY4wI4aVtvL7B3Q';

const quoteId = 'DVI20251213';

console.log('\n=== FETCHING HOTEL DETAILS ===');
console.log(`Quote ID: ${quoteId}`);
console.log('Endpoint: GET /api/v1/itineraries/hotel_details/' + quoteId);
console.log('\n');

const options = {
  hostname: '127.0.0.1',
  port: 4006,
  path: `/api/v1/itineraries/hotel_details/${quoteId}`,
  method: 'GET',
  headers: {
    'Content-Type': 'application/json',
    'Authorization': `Bearer ${token}`
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
      
      // Analyze hotel assignments
      if (parsed.hotels && Array.isArray(parsed.hotels)) {
        console.log('\n=== HOTEL ANALYSIS ===');
        parsed.hotels.forEach((hotel, idx) => {
          console.log(`\nHotel ${idx + 1}:`);
          console.log(`  Day: ${hotel.day}`);
          console.log(`  Destination: ${hotel.destination}`);
          console.log(`  Hotel Name: ${hotel.hotel_name}`);
          console.log(`  Category: ${hotel.category}`);
          console.log(`  Room Type: ${hotel.room_type}`);
          console.log(`  Meal Plan: ${hotel.meal_plan}`);
          
          // Flag suspicious assignments
          if (hotel.destination === 'Chennai' && hotel.hotel_name && hotel.hotel_name.toLowerCase().includes('ooty')) {
            console.log('  ⚠️  WARNING: Chennai has Ooty hotel!');
          }
          if (hotel.destination === 'Bangalore' && hotel.hotel_name && hotel.hotel_name.toLowerCase().includes('ooty')) {
            console.log('  ⚠️  WARNING: Bangalore has Ooty hotel!');
          }
        });
      }
      
      console.log('\n✅ Done!');
    } catch (e) {
      console.log(data);
    }
    process.exit(0);
  });
});

req.on('error', (error) => {
  console.error('❌ Error:', error.message);
  process.exit(1);
});

req.end();
console.log('Request sent! Waiting for response...\n');
