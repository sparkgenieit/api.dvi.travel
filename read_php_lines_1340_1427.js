const fs = require('fs');

const phpCode = fs.readFileSync('legacy_php/ajax_latest_manage_itineary_opt.php', 'utf8');
const lines = phpCode.split('\n');

console.log('=== PHP LINES 1340-1427 (SOURCE→DESTINATION→VIA processing) ===\n');

for (let i = 1339; i < 1428 && i < lines.length; i++) {
  console.log(`${i+1}: ${lines[i]}`);
}
