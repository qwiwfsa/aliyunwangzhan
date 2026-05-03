# Check what the actual Chinese text should be
with open('D:\\yingyong\\xampp\\htdocs\\hongdu\\news-detail.html', 'rb') as f:
    data = f.read()

# Find the description content
idx = data.find(b'description')
content_start = data.find(b'content="', idx)
end_quote = data.find(b'"', content_start + 9)
desc_bytes = data[content_start + 9:end_quote]
print(f"Description bytes (hex): {desc_bytes.hex()}")
print(f"Length: {len(desc_bytes)} bytes")

# Let's see the full decoded text
text = data.decode('utf-8')

# Check for specific garbled areas
import re
garbled = re.findall(r'[\u0400-\u04ff\u0080-\u00ff]', text[:500])
print(f"\nGarbled chars in first 500 chars: {garbled}")
print(f"Number of garbled: {len(garbled)}")

# Let's check all non-ASCII chars in first 500
for i, c in enumerate(text[:500]):
    if ord(c) > 127:
        print(f"  pos {i}: U+{ord(c):04X} '{c}'")
