import os, re

with open('index.html.gbk.bak', 'rb') as fp:
    raw = fp.read()

# Find meta description
desc_idx = raw.find(b'content="Yoo')
if desc_idx > 0:
    end_quote = raw.find(b'"', desc_idx + 15)
    content_bytes = raw[desc_idx+9:end_quote]
    print(f'Meta description raw bytes (hex): {content_bytes.hex()}')
    print(f'Length: {len(content_bytes)}')
    
    # Try both decodings
    try:
        gbk_decoded = content_bytes.decode('gbk')
        print(f'GBK decode: {gbk_decoded}')
    except:
        print(f'GBK decode: FAILED')
    
    try:
        utf8_decoded = content_bytes.decode('utf-8', errors='replace')
        print(f'UTF-8 decode: {utf8_decoded}')
    except:
        print(f'UTF-8 decode: FAILED')

print()
# Look at a section with many Chinese bytes
# Find first Chinese character range
for section_start in range(0, len(raw), 1000):
    if raw[section_start] >= 0x80:
        chunk = raw[section_start:section_start+200]
        print(f'At offset {section_start}:')
        print(f'  Hex: {chunk.hex()}')
        try:
            print(f'  GBK: {chunk.decode("gbk", errors="replace")}')
        except:
            pass
        try:
            print(f'  UTF-8: {chunk.decode("utf-8", errors="replace")}')
        except:
            pass
        break

# Also check what happens if we decode the whole file as UTF-8
print()
print(f'File size: {len(raw)}')
try:
    utf8_text = raw.decode('utf-8', errors='replace')
    # Count how many replacement characters
    replacements = utf8_text.count('\ufffd')
    chinese_chars = sum(1 for c in utf8_text if ord(c) > 0x4e00)
    print(f'UTF-8 decode: {len(utf8_text)} chars, replacements={replacements}, chinese_chars={chinese_chars}')
except Exception as e:
    print(f'UTF-8 decode error: {e}')

# Try big5 / other encodings
for enc in ['big5', 'gb2312', 'gb18030', 'cp936']:
    try:
        decoded = raw.decode(enc)
        chinese = sum(1 for c in decoded if ord(c) > 0x4e00)
        replacements = decoded.count('\ufffd')
        print(f'{enc}: OK, chinese={chinese}, replacements={replacements}')
        if replacements == 0 and chinese > 100:
            sample = ''.join(c for c in decoded if ord(c) > 0x4e00)[:30]
            print(f'  Sample: {sample}')
    except Exception as e:
        print(f'{enc}: FAILED - {str(e)[:80]}')
