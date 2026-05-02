import os

# For test-api.html and test-news-load.html, the GBK decode had replacement chars
# meaning some bytes were invalid GBK. Those are likely UTF-8 encoded SEO script
# bytes that got mixed in. We need a smarter approach.

for f in ['test-api.html', 'test-news-load.html']:
    bak = f + '.gbk.bak'
    if not os.path.exists(bak):
        print(f'{f}: no backup found, skipping')
        continue
    
    with open(bak, 'rb') as fp:
        raw = fp.read()
    
    # Use GBK only for content that is valid GBK, preserve the rest
    # Strategy: decode byte by byte
    result = []
    i = 0
    while i < len(raw):
        b = raw[i]
        # ASCII byte - always safe
        if b < 0x80:
            result.append(chr(b))
            i += 1
        # Try 2-byte GBK sequence
        elif i + 1 < len(raw) and 0x81 <= raw[i] <= 0xFE and 0x40 <= raw[i+1] <= 0xFE:
            pair = raw[i:i+2]
            try:
                ch = pair.decode('gbk')
                result.append(ch)
                i += 2
            except:
                # Not valid GBK, try UTF-8 2-byte
                if 0xC0 <= b < 0xE0 and i+1 < len(raw) and 0x80 <= raw[i+1] < 0xC0:
                    try:
                        ch = raw[i:i+2].decode('utf-8')
                        result.append(ch)
                        i += 2
                    except:
                        result.append(chr(0xFFFD))
                        i += 1
                else:
                    result.append(chr(0xFFFD))
                    i += 1
        # Try 3-byte UTF-8
        elif 0xE0 <= b < 0xF0 and i+2 < len(raw):
            triple = raw[i:i+3]
            try:
                ch = triple.decode('utf-8')
                result.append(ch)
                i += 3
            except:
                result.append(chr(0xFFFD))
                i += 1
        # Try single-byte GBK extended area
        elif 0x81 <= b <= 0xA0:
            # Could be GBK first byte but no second byte available or invalid
            result.append(chr(0xFFFD))
            i += 1
        else:
            result.append(chr(0xFFFD))
            i += 1
    
    text = ''.join(result)
    replacements = text.count(chr(0xFFFD))
    chinese_chars = sum(1 for c in text if ord(c) > 0x4e00 and ord(c) != 0xFFFD)
    print(f'{f}: decoded, replacements={replacements}, chinese_chars={chinese_chars}')
    
    # Write
    utf8_bytes = text.encode('utf-8')
    with open(f, 'wb') as fp:
        fp.write(utf8_bytes)
    print(f'  Saved as UTF-8 ({len(utf8_bytes)} bytes)')

print('\nDone fixing test files.')
