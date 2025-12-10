const fs = require('fs');
const path = require('path');

const tmpDir = path.join(process.cwd(), 'tmp');

// Find all log files
const files = fs.readdirSync(tmpDir)
  .filter(f => f.startsWith('hotspot_optimization_') && f.endsWith('.log'))
  .map(f => ({
    name: f,
    path: path.join(tmpDir, f),
    time: fs.statSync(path.join(tmpDir, f)).mtime
  }))
  .sort((a, b) => b.time - a.time);

if (files.length === 0) {
  console.log('No log files found in tmp/');
  process.exit(1);
}

const latestLog = files[0];
console.log(`\n=== READING LATEST LOG: ${latestLog.name} ===`);
console.log(`Created: ${latestLog.time.toISOString()}\n`);

const content = fs.readFileSync(latestLog.path, 'utf-8');
console.log(content);
