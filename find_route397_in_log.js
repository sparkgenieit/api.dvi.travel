const fs = require('fs');
const path = require('path');

// Read the latest log file
const tmpDir = path.join(__dirname, 'tmp');
const files = fs.readdirSync(tmpDir)
  .filter(f => f.startsWith('optimization-') && f.endsWith('.log'))
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
console.log('Reading:', files[0].name);
console.log('');

const content = fs.readFileSync(latestLog, 'utf8');
const lines = content.split('\n');

// Find Route 397 timeline generation
const route397Lines = lines.filter(l => l.includes('Route 397') || l.includes('routeStartTime'));

console.log('=== ROUTE 397 LOG ENTRIES ===\n');
route397Lines.forEach(l => console.log(l));
