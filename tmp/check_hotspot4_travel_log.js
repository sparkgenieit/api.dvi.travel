const fs = require('fs');
const path = require('path');

const tmpDir = path.join(__dirname);
const files = fs.readdirSync(tmpDir)
  .filter(f => f.startsWith('hotspot_optimization_') && f.endsWith('.log'))
  .map(f => ({
    name: f,
    time: fs.statSync(path.join(tmpDir, f)).mtime.getTime()
  }))
  .sort((a, b) => b.time - a.time);

if (files.length === 0) {
  console.log('No log files found');
  process.exit(0);
}

const latestLog = path.join(tmpDir, files[0].name);
const content = fs.readFileSync(latestLog, 'utf-8');

// Find lines related to hotspot 4 travel calculation
const lines = content.split('\n');
let capturing = false;
let captureLines = [];

for (let i = 0; i < lines.length; i++) {
  const line = lines[i];
  
  // Start capturing when we see hotspot 4 travel
  if (line.includes('hotspot 4') || line.includes('hotspot_ID: 4')) {
    capturing = true;
    captureLines = [];
  }
  
  if (capturing) {
    captureLines.push(line);
    
    // Stop after we see the travel time result
    if (line.includes('[Haversine]') && line.includes('Duration:')) {
      console.log(captureLines.join('\n'));
      console.log('\n---\n');
      capturing = false;
      captureLines = [];
    }
  }
}
