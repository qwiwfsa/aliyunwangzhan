import re, os

base = 'D:\\yingyong\\xampp\\htdocs\\hongdu'

for fname in ['news-detail.html', 'case-detail.html']:
    p = os.path.join(base, fname)
    with open(p, 'rb') as f:
        raw = f.read()
    text = raw.decode('utf-8')
    
    # Count chars outside CJK/ASCII, excluding CJK punctuation ranges
    bad = []
    for i, c in enumerate(text[:2000]):
        code = ord(c)
        if code > 127 and not (0x4e00 <= code <= 0x9fff) and not (0x3000 <= code <= 0x303f) and not (0xff00 <= code <= 0xffef):
            bad.append((i, c, hex(code)))
    
    print(fname + ': ' + str(len(bad)) + ' non-CJK non-ASCII chars in first 2000')
    for pos, c, code in bad[:15]:
        print(f'  pos {pos}: U+{code[2:]} {c}')
    
    # Check current Chinese around Yao
    for m in re.finditer(r'Yao.', text[:500]):
        print(f'  Yao context at {m.start()}: {m.group()} (ord={ord(m.group()[-1]):04X})')
    print()
