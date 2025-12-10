const fs = require('fs');

const phpCode = fs.readFileSync('legacy_php/sql_functions.php', 'utf8');
const lines = phpCode.split('\n');

// Find includeHotspotInItinerary function
const startLine = 15061;
const endLine = 15200;

console.log('=== PHP includeHotspotInItinerary OPERATING HOURS LOGIC (Lines 15061-15200) ===\n');

for (let i = startLine - 1; i < endLine && i < lines.length; i++) {
  console.log(`${i+1}: ${lines[i]}`);
}
