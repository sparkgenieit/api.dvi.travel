const fs = require('fs');

const phpCode = fs.readFileSync('legacy_php/sql_functions.php', 'utf8');
const lines = phpCode.split('\n');

console.log('=== PHP checkHOTSPOTOPERATINGHOURS FUNCTION ===\n');

// Find the function (around line 10388)
for (let i = 10385; i < 10500 && i < lines.length; i++) {
  console.log(`${i+1}: ${lines[i]}`);
}
