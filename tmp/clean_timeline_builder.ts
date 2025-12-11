// Script to remove all this.log() calls from timeline.builder.ts
const fs = require('fs');
const path = require('path');

const filePath = path.join(__dirname, '..', 'src', 'modules', 'itineraries', 'engines', 'helpers', 'timeline.builder.ts');
let content = fs.readFileSync(filePath, 'utf8');

// Remove all this.log() calls (single line and multi-line)
let lines = content.split('\n');
let newLines = [];
let skipUntilSemicolon = false;

for (let i = 0; i < lines.length; i++) {
  const line = lines[i];
  
  // Check if line contains this.log(
  if (line.includes('this.log(')) {
    // Check if it's a complete statement on one line
    if (line.includes(');')) {
      // Skip this entire line
      continue;
    } else {
      // Multi-line log call - skip until we find the closing );
      skipUntilSemicolon = true;
      continue;
    }
  }
  
  if (skipUntilSemicolon) {
    // Look for the closing );
    if (line.includes(');')) {
      skipUntilSemicolon = false;
    }
    continue;
  }
  
  newLines.push(line);
}

content = newLines.join('\n');

// Remove unused imports
content = content.replace(/import \* as fs from ["']fs["'];\n?/, '');
content = content.replace(/import \* as path from ["']path["'];\n?/, '');

// Write back
fs.writeFileSync(filePath, content, 'utf8');

console.log('Successfully removed all this.log() calls and unused imports!');
