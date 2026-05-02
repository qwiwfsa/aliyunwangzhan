import os, re

# Read the ORIGINAL backup (before any fix)
with open('index.html.gbk.bak', 'rb') as fp:
    raw = fp.read()

# Decode as GBK
text = raw.decode('gbk')

# Write decoded text to a verification file
with open('_verify_gbk_output.txt', 'w', encoding='utf-8') as f:
    f.write(text[:5000])

print("Written _verify_gbk_output.txt - check for Chinese readability")

# Now let's verify the FIXED file
with open('index.html', 'rb') as fp:
    fixed_raw = fp.read()

fixed_text = fixed_raw.decode('utf-8')
with open('_verify_utf8_output.txt', 'w', encoding='utf-8') as f:
    f.write(fixed_text[:5000])

print("Written _verify_utf8_output.txt - check for Chinese readability")

# Find meta description in fixed file
desc_match = re.search(r'meta name="description" content="([^"]+)"', fixed_text)
if desc_match:
    desc = desc_match.group(1)
    print(f'\nMeta description in FIXED file: {desc}')

# Also read the GBK decoded text to check  
with open('_verify_gbk_output.txt', 'r', encoding='utf-8') as f:
    gbk_text = f.read()
print(f'\nGBK decoded (first 300 chars): {gbk_text[:300]}')
