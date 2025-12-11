const fs = require('fs');
const path = require('path');

// Find the latest log file
const tmpDir = path.join(__dirname);
const files = fs.readdirSync(tmpDir);
const logFiles = files.filter(f => f.startsWith('optimization_') && f.endsWith('.log'));
logFiles.sort().reverse();
const latestLog = logFiles[0];

if (!latestLog) {
  console.log('No log file found');
  process.exit(1);
}

console.log(`Reading: ${latestLog}\n`);
const content = fs.readFileSync(path.join(tmpDir, latestLog), 'utf8');

// Find Route 422 processing and hotspot 18 specifically
const lines = content.split('\n');
let inRoute422 = false;
let foundHotspot18 = false;

for (let i = 0; i < lines.length; i++) {
  const line = lines[i];
  
  // Track when we enter Route 422
  if (line.includes('Processing route 422')) {
    inRoute422 = true;
    console.log('=== ROUTE 422 PROCESSING ===');
  }
  
  // Track when we leave Route 422
  if (inRoute422 && line.includes('Processing route') && !line.includes('route 422')) {
    inRoute422 = false;
    if (!foundHotspot18) {
      console.log('\n❌ Hotspot 18 was never considered for Route 422!\n');
    }
    break;
  }
  
  // Show hotspot 18 related lines
  if (inRoute422 && line.includes('hotspot 18')) {
    foundHotspot18 = true;
    console.log(line);
  }
  
  // Show operating hours check details
  if (inRoute422 && foundHotspot18 && 
      (line.includes('Checking operating hours') || 
       line.includes('timing window') ||
       line.includes('visit window') ||
       line.includes('not open') ||
       line.includes('operating hours OK'))) {
    console.log(line);
  }
}

if (!inRoute422 && !foundHotspot18) {
  console.log('Route 422 or hotspot 18 not found in log');
}
