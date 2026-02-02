#!/usr/bin/env node

/**
 * Test script for preview-add endpoint with hotspot 41
 */

const http = require('http');

const token = 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJzdWIiOiIxIiwiZW1haWwiOiJhZG1pbkBkdmkuY28uaW4iLCJyb2xlIjoxLCJhZ2VudElkIjowLCJzdGFmZklkIjowLCJndWlkZUlkIjowLCJpYXQiOjE3Njk0ODc0OTQsImV4cCI6MTc3MDA5MjI5NH0.ImUibp_gsVjgkHWMrzAqqgwDQYhW92KI3YGBvAz0t5E';

const requestBody = {
  planId: 17,
  routeId: 354,
  hotspotId: 505,
};

console.log('\n════════════════════════════════════════════════════════════════');
console.log('🔍 PREVIEW-ADD TEST - Hotspot 505 with Conflict Detection');
console.log('════════════════════════════════════════════════════════════════\n');

console.log('Request:');
console.log(JSON.stringify(requestBody, null, 2));
console.log('\n');

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
  console.log(`✅ Response Status: ${res.statusCode}\n`);

  let data = '';
  res.on('data', (chunk) => {
    data += chunk;
  });

  res.on('end', () => {
    try {
      const parsed = JSON.parse(data);

      if (res.statusCode === 200 || res.statusCode === 201) {
        console.log('📄 RESPONSE:');

        // Show summary
        console.log(`\nFullTimeline entries: ${parsed.fullTimeline.length}`);
        console.log(`Included routes: ${JSON.stringify(parsed.includedRouteIds)}`);
        console.log(`Next route included: ${parsed.nextRouteIncluded}`);

        // Find hotspot 505 entries
        console.log('\n🔎 SEARCHING FOR HOTSPOT 505 IN RESPONSE:');
        const hotspot505Entries = parsed.fullTimeline.filter(
          (item) => item.hotspot_ID === 505
        );

        if (hotspot505Entries.length > 0) {
          console.log(`✅ FOUND ${hotspot505Entries.length} entries for hotspot 505:`);
          for (const entry of hotspot505Entries) {
            console.log(`   - Type: ${entry.type}, ItemType: ${entry.item_type}, Order: ${entry.hotspot_order}`);
            console.log(`     Time: ${entry.timeRange}`);
            console.log(`     Text: ${entry.text}`);
            console.log(`     Conflict: ${entry.isConflict || false}`);
            if (entry.isConflict) {
              console.log(`     ⚠️  Conflict Reason: ${entry.conflictReason}`);
            }
          }
        } else {
          console.log('❌ HOTSPOT 505 NOT FOUND in fullTimeline');
          console.log('   This means the old blocking behavior is still active!');
        }

        // Show all attractions in route 354
        console.log('\n📍 ALL ATTRACTIONS IN ROUTE 354:');
        const route354Attractions = parsed.fullTimeline.filter(
          (item) => item.itinerary_route_ID === 354 && item.type === 'attraction'
        );
        console.log(`Found ${route354Attractions.length} attractions:`);
        for (const attr of route354Attractions) {
          console.log(`   - ID: ${attr.hotspot_ID}, Name: ${attr.text}, Order: ${attr.hotspot_order}`);
          if (attr.isConflict) {
            console.log(`     ⚠️  CONFLICT: ${attr.conflictReason}`);
          }
        }

        // Show newHotspot field
        console.log('\n🎯 NEW HOTSPOT FIELD:');
        if (parsed.newHotspot) {
          console.log(`✅ newHotspot is set:`);
          console.log(`   ID: ${parsed.newHotspot.hotspot_ID}`);
          console.log(`   Type: ${parsed.newHotspot.type}`);
          console.log(`   Text: ${parsed.newHotspot.text}`);
        } else {
          console.log('❌ newHotspot is NULL or missing');
        }
      } else {
        console.log('❌ ERROR:');
        console.log(JSON.stringify(parsed, null, 2));
      }
    } catch (e) {
      console.log('Failed to parse response:');
      console.log(data);
    }

    console.log('\n════════════════════════════════════════════════════════════════\n');
    process.exit(0);
  });
});

req.on('error', (error) => {
  console.error('❌ Request Error:', error.message);
  console.log('\n💡 Make sure the NestJS server is running on localhost:4006');
  process.exit(1);
});

req.write(postData);
req.end();
