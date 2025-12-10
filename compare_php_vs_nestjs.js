const http = require('http');
const querystring = require('querystring');

const token = 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJzdWIiOiIxIiwiZW1haWwiOiJhZG1pbkBkdmkuY28uaW4iLCJyb2xlIjoxLCJpYXQiOjE3NjQ5OTg3ODIsImV4cCI6MTc2NTYwMzU4Mn0.UFtOqNeZo9JWD2loHDI8WDTr_kEVPR1v1jJwSXY0FUY';

// Fetch PHP HTML
function fetchPHPHTML() {
  return new Promise((resolve, reject) => {
    const postData = querystring.stringify({ _ID: 2 });
    
    const options = {
      hostname: 'localhost',
      port: 80,
      path: '/dvi/engine/ajax/ajax_latest_itineary_step2_form.php?type=show_form&selected_group_type=1',
      method: 'POST',
      headers: {
        'Content-Type': 'application/x-www-form-urlencoded',
        'Content-Length': Buffer.byteLength(postData)
      }
    };
    
    const req = http.request(options, (res) => {
      let data = '';
      res.on('data', chunk => data += chunk);
      res.on('end', () => resolve(data));
    });
    
    req.on('error', reject);
    req.write(postData);
    req.end();
  });
}

// Fetch NestJS API
function fetchNestJSAPI() {
  return new Promise((resolve, reject) => {
    const options = {
      hostname: 'localhost',
      port: 4006,
      path: '/api/v1/itineraries/details/DVI2025122',
      method: 'GET',
      headers: {
        'Authorization': `Bearer ${token}`,
        'Content-Type': 'application/json'
      }
    };
    
    const req = http.request(options, (res) => {
      let data = '';
      res.on('data', chunk => data += chunk);
      res.on('end', () => resolve(JSON.parse(data)));
    });
    
    req.on('error', reject);
    req.end();
  });
}

// Parse PHP HTML for time ranges
function parsePHPTimes(html) {
  const times = [];
  
  // Extract "Start your Journey" time
  const startMatch = html.match(/Start your Journey.*?(\d{1,2}:\d{2}\s*[AP]M)\s*-\s*(\d{1,2}:\d{2}\s*[AP]M)/s);
  if (startMatch) {
    times.push({
      type: 'start',
      time: `${startMatch[1]} - ${startMatch[2]}`
    });
  }
  
  // Extract "Travelling from" segments
  const travelRegex = /Travelling from\s+<strong>(.*?)<\/strong>\s+to\s+<strong>(.*?)<\/strong>.*?(\d{1,2}:\d{2}\s*[AP]M)\s*-\s*(\d{1,2}:\d{2}\s*[AP]M)/gs;
  let match;
  while ((match = travelRegex.exec(html)) !== null) {
    times.push({
      type: 'travel',
      from: match[1].trim(),
      to: match[2].trim(),
      time: `${match[3]} - ${match[4]}`
    });
  }
  
  // Extract attraction visits
  const attractionRegex = /<h5[^>]*>\s*(.*?)\s*<\/h5>.*?<i class="fa fa-clock-o"><\/i>\s*(\d{1,2}:\d{2}\s*[AP]M)\s*-\s*(\d{1,2}:\d{2}\s*[AP]M)/gs;
  while ((match = attractionRegex.exec(html)) !== null) {
    times.push({
      type: 'attraction',
      name: match[1].trim(),
      time: `${match[2]} - ${match[3]}`
    });
  }
  
  return times;
}

async function compare() {
  try {
    console.log('=== FETCHING PHP HTML ===\n');
    const phpHTML = await fetchPHPHTML();
    
    console.log('=== FETCHING NESTJS API ===\n');
    const nestjsData = await fetchNestJSAPI();
    
    console.log('=== PHP PARSED TIMES (Day 1) ===\n');
    const phpTimes = parsePHPTimes(phpHTML);
    phpTimes.forEach(t => {
      if (t.type === 'start') {
        console.log(`START: ${t.time}`);
      } else if (t.type === 'travel') {
        console.log(`TRAVEL: ${t.from} → ${t.to}`);
        console.log(`  Time: ${t.time}`);
      } else if (t.type === 'attraction') {
        console.log(`VISIT: ${t.name}`);
        console.log(`  Time: ${t.time}`);
      }
    });
    
    console.log('\n=== NESTJS API TIMES (Day 1) ===\n');
    const day1 = nestjsData.days?.[0];
    if (day1) {
      day1.segments?.forEach(seg => {
        if (seg.type === 'start') {
          console.log(`START: ${seg.timeRange}`);
        } else if (seg.type === 'travel') {
          console.log(`TRAVEL: ${seg.from} → ${seg.to}`);
          console.log(`  Time: ${seg.timeRange}`);
        } else if (seg.type === 'attraction') {
          console.log(`VISIT: ${seg.name}`);
          console.log(`  Time: ${seg.visitTime}`);
        }
      });
    }
    
    console.log('\n=== COMPARISON REPORT ===\n');
    
    // Compare each segment
    for (let i = 0; i < phpTimes.length; i++) {
      const php = phpTimes[i];
      const nest = day1?.segments?.[i];
      
      if (!nest) {
        console.log(`❌ Missing segment ${i + 1} in NestJS`);
        continue;
      }
      
      const phpTime = php.time;
      const nestTime = nest.timeRange || nest.visitTime;
      
      if (phpTime === nestTime) {
        console.log(`✅ Segment ${i + 1}: ${php.type} - MATCH`);
      } else {
        console.log(`❌ Segment ${i + 1}: ${php.type} - MISMATCH`);
        console.log(`   PHP:    ${phpTime}`);
        console.log(`   NestJS: ${nestTime}`);
      }
    }
    
    // Save full PHP HTML for inspection
    const fs = require('fs');
    fs.writeFileSync('tmp/php_html_output.html', phpHTML);
    console.log('\n✅ Full PHP HTML saved to tmp/php_html_output.html');
    
  } catch (error) {
    console.error('Error:', error.message);
  }
}

compare();
