#!/usr/bin/env node

/**
 * Test that saves server logs to file
 */

const http = require('http');
const fs = require('fs');

const token = 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJzdWIiOiIxIiwiZW1haWwiOiJhZG1pbkBkdmkuY28uaW4iLCJyb2xlIjoxLCJhZ2VudElkIjowLCJzdGFmZklkIjowLCJndWlkZUlkIjowLCJpYXQiOjE3Njk0ODc0OTQsImV4cCI6MTc3MDA5MjI5NH0.ImUibp_gsVjgkHWMrzAqqgwDQYhW92KI3YGBvAz0t5E';

const requestBody = {
  planId: 17,
  routeId: 348,
  hotspotId: 41,
};

const logFile = './hotspot-test-result.log';
let logOutput = '';

const log = (msg) => {
  logOutput += msg + '\n';
  console.log(msg);
};

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

      log('\n════════════════════════════════════════════════════════════════');
      log('🔍 HOTSPOT 41 TEST RESULT');
      log('════════════════════════════════════════════════════════════════\n');

      log('REQUEST: planId=17, routeId=348, hotspotId=41');
      log('RESPONSE STATUS:', res.statusCode);

      const hs41 = parsed.fullTimeline?.filter(item => item.hotspot_ID === 41) || [];
      
      if (hs41.length > 0) {
        log('\n✅ SUCCESS! Hotspot 41 FOUND in fullTimeline');
        log(`   Entries: ${hs41.length}`);
        hs41.forEach(entry => {
          log(`   - Time: ${entry.timeRange}, Type: ${entry.type}, Text: ${entry.text}`);
        });
      } else {
        log('\n❌ FAILED! Hotspot 41 NOT in fullTimeline');
        log(`   Total timeline entries: ${parsed.fullTimeline?.length || 0}`);
      }

      log('\nNEWHOTSPOT field:', parsed.newHotspot ? 'SET' : 'NULL');

      log('\n════════════════════════════════════════════════════════════════\n');

      // Save to file
      fs.writeFileSync(logFile, logOutput, 'utf-8');
      log(`\n📝 Results saved to: ${logFile}`);
      
    } catch (e) {
      log('Error parsing response:', e.message);
      fs.writeFileSync(logFile, logOutput, 'utf-8');
    }

    process.exit(0);
  });
});

req.on('error', (error) => {
  log('❌ Request Error:', error.message);
  fs.writeFileSync(logFile, logOutput, 'utf-8');
  process.exit(1);
});

req.write(postData);
req.end();
