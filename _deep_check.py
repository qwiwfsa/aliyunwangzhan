import os, re

for f in ['test-api.html', 'test-news-load.html']:
    with open(f, 'rb') as fp:
        raw = fp.read()
    
    print(f'=== {f} ===')
    # First 500 bytes as hex decode attempt
    print(f'Full file bytes: {len(raw)}')
    
    # Try to find where the problem is by doing a strict utf-8 decode
    try:
        text = raw.decode('utf-8', errors='strict')
        print(f'UTF-8 strict: OK')
    except UnicodeDecodeError as e:
        print(f'UTF-8 strict: FAILED at byte {e.start}')
        context = raw[max(0,e.start-10):e.start+20]
        print(f'  Context hex: {context.hex()}')
        print(f'  Context bytes: {context}')
        # Check if those bytes decode as GBK
        try:
            gbk_chunk = context.decode('gbk')
            print(f'  GBK decode of chunk: {repr(gbk_chunk)}')
        except:
            print(f'  GBK also fails on chunk')
    
    # Try to see if the file is actually mostly UTF-8 with a few GBK bytes
    # Check for UTF-8 BOM first:
    print(f'  Has BOM: {raw[:3] == bytes([0xef,0xbb,0xbf])}')
    
    # Count bytes that would be invalid in UTF-8
    bad_utf8 = 0
    i = 0
    while i < len(raw):
        b = raw[i]
        if b < 0x80:
            i += 1
        elif b >= 0xC0 and b < 0xE0:
            if i+1 < len(raw) and 0x80 <= raw[i+1] < 0xC0:
                i += 2
            else:
                bad_utf8 += 1
                i += 1
        elif b >= 0xE0 and b < 0xF0:
            if i+2 < len(raw) and 0x80 <= raw[i+1] < 0xC0 and 0x80 <= raw[i+2] < 0xC0:
                i += 3
            else:
                bad_utf8 += 1
                i += 1
        elif b >= 0xF0 and b < 0xF8:
            if i+3 < len(raw) and all(0x80 <= raw[i+j] < 0xC0 for j in range(1,4)):
                i += 4
            else:
                bad_utf8 += 1
                i += 1
        else:
            # Single bytes in 0x80-0xBF or 0xF8-0xFF are always invalid UTF-8
            bad_utf8 += 1
            i += 1
    print(f'  Invalid UTF-8 bytes: {bad_utf8}')

    # Count GBK bytes (0x81-0xFE followed by 0x40-0xFE)
    gbk_count = 0
    i = 0
    while i < len(raw) - 1:
        if 0x81 <= raw[i] <= 0xFE and 0x40 <= raw[i+1] <= 0xFE:
            gbk_count += 1
            i += 2
        else:
            i += 1
    print(f'  Potential GBK 2-byte sequences: {gbk_count}')
    
    # Check if the content between <body> and </body> has GBK
    body_start = raw.find(b'<body')
    body_end = raw.find(b'</body>')
    if body_start > 0 and body_end > body_start:
        body_content = raw[body_start:body_end+7]
        body_gbk = 0
        i = 0
        while i < len(body_content) - 1:
            if 0x81 <= body_content[i] <= 0xFE and 0x40 <= body_content[i+1] <= 0xFE:
                body_gbk += 1
                i += 2
            else:
                i += 1
        print(f'  Body section GBK 2-byte: {body_gbk} / {len(body_content)} bytes')
    print()
