const fs = require('fs');
const path = require('path');

// Find latest log file
const tmpDir = path.join(__dirname, 'tmp');
const files = fs.readdirSync(tmpDir)
  .filter(f => f.startsWith('hotspot_optimization_'))
  .map(f => ({
    name: f,
    time: fs.statSync(path.join(tmpDir, f)).mtime.getTime()
  }))
  .sort((a, b) => b.time - a.time);

if (files.length === 0) {
  console.log('No log files found');
  process.exit(0);
}

const logFile = path.join(tmpDir, files[0].name);
const content = fs.readFileSync(logFile, 'utf8');
const lines = content.split('\n');

console.log('Looking for Chennai → Pondicherry route...\n');

let inRoute347 = false;
let lineCount = 0;

for (let i = 0; i < lines.length; i++) {
  const line = lines[i];
  
  if (line.includes('Chennai') && line.includes('Pondicherry') && !line.includes('Airport')) {
    inRoute347 = true;
    lineCount = 0;
  }
  
  if (inRoute347) {
    console.log(line);
    lineCount++;
    
    if (lineCount > 50 || line.includes('Returning') || (line.includes('Route') && lineCount > 5 && !line.includes('Chennai'))) {
      break;
    }
  }
}
