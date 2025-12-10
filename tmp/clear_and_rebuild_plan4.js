const { PrismaClient } = require('@prisma/client');

async function clearPlan4() {
  const prisma = new PrismaClient();
  
  try {
    console.log('Clearing plan 4 data...');
    
    // Delete in correct order due to foreign keys
    await prisma.$executeRaw`DELETE FROM dvi_itinerary_route_hotspot_parking_charge WHERE itinerary_plan_id = 4`;
    console.log('✓ Deleted parking charges');
    
    await prisma.$executeRaw`DELETE FROM dvi_itinerary_route_hotspot_entry_cost_details WHERE itinerary_plan_id = 4`;
    console.log('✓ Deleted hotspot entry costs');
    
    await prisma.$executeRaw`DELETE FROM dvi_itinerary_route_hotspot_details WHERE itinerary_plan_id = 4`;
    console.log('✓ Deleted route hotspot details');
    
    await prisma.$executeRaw`DELETE FROM dvi_itinerary_plan_details WHERE itinerary_plan_id = 4`;
    console.log('✓ Deleted plan details');
    
    // Now trigger rebuild via API
    const http = require('http');
    
    return new Promise((resolve) => {
      const postData = JSON.stringify({ itinerary_id: 4 });
      
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
        res.on('data', chunk => data += chunk);
        res.on('end', () => {
          if (res.statusCode >= 200 && res.statusCode < 300) {
            console.log('✓ Rebuild triggered via API');
            resolve();
          } else {
            console.log('Response:', data);
            resolve();
          }
        });
      });
      
      req.on('error', (e) => {
        console.log('Could not trigger rebuild via API:', e.message);
        resolve();
      });
      
      req.write(postData);
      req.end();
      
      // Timeout after 5 seconds
      setTimeout(resolve, 5000);
    });
  } finally {
    await prisma.$disconnect();
  }
}

clearPlan4()
  .then(() => {
    console.log('\nDone! Waiting for rebuild to complete...');
    setTimeout(() => process.exit(0), 3000);
  })
  .catch(err => {
    console.error('Error:', err);
    process.exit(1);
  });
