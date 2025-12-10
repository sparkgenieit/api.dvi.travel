const http = require('http');

const options = {
  hostname: '127.0.0.1',
  port: 4006,
  path: '/api/v1/itineraries/5',
  method: 'GET',
  headers: {
    'Content-Type': 'application/json',
  }
};

const req = http.request(options, (res) => {
  let data = '';
  
  res.on('data', (chunk) => {
    data += chunk;
  });
  
  res.on('end', () => {
    console.log('Raw response:', data);
    const result = JSON.parse(data);
    
    if (!result.routes || result.routes.length === 0) {
      console.log('❌ No routes found');
      return;
    }
    
    const route1 = result.routes[0];
    
    console.log('\n=== ROUTE 1 (Route 397) ===');
    console.log('Start:', route1.start_from);
    console.log('End:', route1.end_destination);
    console.log('Route Start Time:', route1.route_start_time);
    console.log('Route End Time:', route1.route_end_time);
    
    console.log('\n=== TIMELINE ===');
    route1.hotspots.forEach(h => {
      console.log(`${h.start_time} - ${h.end_time}: ${h.place_name} (hotspot: ${h.hotspot_id})`);
    });
    
    const hotspotIds = route1.hotspots
      .filter(h => h.hotspot_id !== null)
      .map(h => h.hotspot_id);
    
    console.log('\n=== HOTSPOT IDs ===');
    console.log(hotspotIds);
    
    console.log('\n✅ Expected: [5]');
    console.log('✅ PHP Route 1 has: [5]');
    console.log(hotspotIds.toString() === '5' ? '✅ MATCH!' : '❌ MISMATCH!');
  });
});

req.on('error', (error) => {
  console.error('Error:', error);
});

req.end();
