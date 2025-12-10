const fs = require('fs');

// Read PHP file
const phpCode = fs.readFileSync('legacy_php/ajax_latest_manage_itineary_opt.php', 'utf8');

// Find the destination hotspot categorization logic
const lines = phpCode.split('\n');

console.log('=== SEARCHING FOR DESTINATION HOTSPOT LOGIC ===\n');

// Search for lines related to destination hotspot categorization
for (let i = 0; i < lines.length; i++) {
  const line = lines[i];
  if (line.includes('destination_hotspots') || 
      (line.includes('$destination') && line.includes('hotspot')) ||
      line.includes('DESTINATION') && line.includes('array_push')) {
    console.log(`Line ${i+1}: ${line.trim()}`);
    // Show context
    if (i > 0) console.log(`  ${i}: ${lines[i-1].trim()}`);
    if (i < lines.length - 1) console.log(`  ${i+2}: ${lines[i+1].trim()}`);
    console.log('');
  }
}

// Search for the categorization loop around line 1196-1211
console.log('\n=== PHP CATEGORIZATION LOGIC (Lines 1190-1220) ===\n');
for (let i = 1189; i < 1220 && i < lines.length; i++) {
  console.log(`${i+1}: ${lines[i]}`);
}
