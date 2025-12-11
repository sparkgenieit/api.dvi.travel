import re

file_path = r"src\modules\itineraries\engines\helpers\timeline.builder.ts"

with open(file_path, 'r', encoding='utf-8') as f:
    content = f.read()

# Remove standalone this.log() lines (including multi-line calls)
# Pattern 1: Remove complete this.log(...) statements including multi-line
content = re.sub(r'\s*this\.log\([^)]*\);\s*\n?', '', content, flags=re.MULTILINE)

# Pattern 2: Remove multi-line this.log calls
lines = content.split('\n')
new_lines = []
skip_until_semicolon = False

for line in lines:
    if 'this.log(' in line and skip_until_semicolon == False:
        if ');' in line:
            # Single line log call - skip it
            continue
        else:
            # Multi-line log call starts - skip until we find the closing );
            skip_until_semicolon = True
            continue
    elif skip_until_semicolon:
        if ');' in line:
            skip_until_semicolon = False
        continue
    else:
        new_lines.append(line)

content = '\n'.join(new_lines)

# Remove the logFilePath property if still there
content = re.sub(r'\s*private logFilePath: string;\s*\n', '', content)

# Remove fs and path imports if not used elsewhere
if 'fs.appendFileSync' not in content and 'fs.existsSync' not in content:
    content = re.sub(r'import \* as fs from ["\']fs["\'];\s*\n', '', content)
if 'path.join' not in content:
    content = re.sub(r'import \* as path from ["\']path["\'];\s*\n', '', content)

with open(file_path, 'w', encoding='utf-8') as f:
    f.write(content)

print(f"Removed all this.log() calls from {file_path}")
