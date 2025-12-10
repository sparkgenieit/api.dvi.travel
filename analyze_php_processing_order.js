const fs = require('fs');

const phpCode = fs.readFileSync('legacy_php/ajax_latest_manage_itineary_opt.php', 'utf8');
const lines = phpCode.split('\n');

console.log('=== PHP PROCESSING ORDER FOR direct=0 (Lines 1235-1450) ===\n');

for (let i = 1234; i < 1450 && i < lines.length; i++) {
  const line = lines[i];
  if (line.trim().startsWith('//') || 
      line.includes('source_location_hotspots') ||
      line.includes('destination_hotspots') ||
      line.includes('via_route_hotspots') ||
      line.includes('foreach') && (line.includes('hotspot') || line.includes('$source') || line.includes('$destination'))) {
    console.log(`${i+1}: ${line}`);
  }
}
