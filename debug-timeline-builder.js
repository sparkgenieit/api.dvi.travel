#!/usr/bin/env node

/**
 * Test with logging enabled to see what's happening
 */

const http = require('http');

const token = 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJzdWIiOiIxIiwiZW1haWwiOiJhZG1pbkBkdmkuY28uaW4iLCJyb2xlIjoxLCJhZ2VudElkIjowLCJzdGFmZklkIjowLCJndWlkZUlkIjowLCJpYXQiOjE3Njk0ODc0OTQsImV4cCI6MTc3MDA5MjI5NH0.ImUibp_gsVjgkHWMrzAqqgwDQYhW92KI3YGBvAz0t5E';

const requestBody = {
  planId: 17,
  routeId: 348,
  hotspotId: 41,
};

console.log('\n════════════════════════════════════════════════════════════════');
console.log('🔍 DETAILED DEBUG - Hotspot 41 Preview');
console.log('════════════════════════════════════════════════════════════════\n');

const postData = JSON.stringify(requestBody);
const options = {
  hostname: '127.0.0.1',
  port: 4006,
  path: '/api/v1/itineraries/hotspots/preview-add',
  method: 'POST',
  headers: {
    'Content-Type': 'application/json',
    'Authorization': `Bearer ${token}`,
    'Content-Length': Buffer.byteLength(postData),
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

      console.log('RESPONSE DATA:');
      console.log(`  newHotspot: ${JSON.stringify(parsed.newHotspot, null, 2)}`);
      console.log(`  otherConflicts: ${parsed.otherConflicts?.length || 0}`);
      console.log(`  droppedItems: ${parsed.droppedItems?.length || 0}`);
      console.log(`  shiftedItems: ${parsed.shiftedItems?.length || 0}`);
      
      console.log(`\nFull Timeline summary:`);
      console.log(`  Total entries: ${parsed.fullTimeline?.length || 0}`);
      console.log(`  Included routes: ${JSON.stringify(parsed.includedRouteIds)}`);
      
      if (parsed.fullTimeline && parsed.fullTimeline.length > 0) {
        console.log(`\nFiltering fullTimeline for hotspot 41...`);
        const hs41 = parsed.fullTimeline.filter(item => item.hotspot_ID === 41);
        console.log(`  Found: ${hs41.length} entries`);
        
        if (hs41.length === 0) {
          console.log(`\n❌ Hotspot 41 is in database but NOT in returned timeline`);
          console.log(`  This suggests it's being filtered somewhere in the timeline builder`);
          
          // Show what's in route 348
          const route348Items = parsed.fullTimeline.filter(item => item.itinerary_route_ID === 348);
          console.log(`\nRoute 348 has ${route348Items.length} timeline items`);
          
          const attractions = route348Items.filter(item => item.type === 'attraction');
          console.log(`  Attractions: ${attractions.length}`);
          attractions.forEach(a => {
            console.log(`    - ${a.hotspot_ID}: ${a.text}`);
          });
        }
      }
      
    } catch (e) {
      console.log('Failed to parse response:', e.message);
      console.log('Raw data:', data.substring(0, 500));
    }

    console.log('\n════════════════════════════════════════════════════════════════\n');
    process.exit(0);
  });
});

req.on('error', (error) => {
  console.error('❌ Request Error:', error.message);
  process.exit(1);
});

req.write(postData);
req.end();
