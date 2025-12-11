// Log the exact hotspot selection order for Route 2
// Read the latest optimization log and search for Route 2 hotspot selection

const fs = require('fs');
const path = require('path');

// Find latest log file
const tmpDir = path.join(__dirname, '.');
const files = fs.readdirSync(tmpDir)
  .filter(f => f.startsWith('hotspot_optimization_'))
  .sort()
  .reverse();

if (files.length === 0) {
  console.log('No optimization log files found');
  process.exit(1);
}

const logFile = path.join(tmpDir, files[0]);
const content = fs.readFileSync(logFile, 'utf8');

console.log(`\n=== Reading ${files[0]} ===\n`);

// Find Route 2 (Route 419) hotspot selection
const route2Lines = content.split('\n').filter(line => 
  line.includes('Route 419') || 
  (line.includes('[fetchSelectedHotspots]') && !line.includes('Route 418') && !line.includes('Route 420'))
);

console.log('=== Route 2 (419) Hotspot Selection ===\n');
route2Lines.slice(0, 50).forEach(line => console.log(line));
