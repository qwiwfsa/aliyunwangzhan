import os

for f in ['test-api.html', 'test-news-load.html']:
    with open(f, 'rb') as fp:
        raw = fp.read()
    print(f'{f}: size={len(raw)}')
    # Find problematic bytes around 0xad
    for i, b in enumerate(raw):
        if b == 0xad:
            print(f'  0xad at position {i}')
            context = raw[max(0,i-10):i+10].hex()
            print(f'  Context: {context}')
            print(f'  Surrounding text: {raw[max(0,i-5):i+10]}')
            break
    
    # Check first 500 bytes hex 
    print(f'  First 100 bytes hex: {raw[:100].hex()}')
    
    # Try to decode with GBK error handling
    try:
        text = raw.decode('gbk', errors='replace')
        chinese = [c for c in text if ord(c) > 0x4e00]
        print(f'  GBK (lenient): chinese chars={len(chinese)}')
        if chinese:
            print(f'  Sample: {"".join(chinese[:20])}')
    except:
        print(f'  GBK (lenient): also failed')
    print()
