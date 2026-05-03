import codecs

with open('D:\\yingyong\\xampp\\htdocs\\hongdu\\news-detail.html', 'rb') as f:
    data = f.read()

# Try different encodings
for enc in ['utf-8', 'gb2312', 'gbk', 'big5', 'shift_jis', 'latin-1', 'cp1252']:
    try:
        s = data.decode(enc)
        # Check if it contains Chinese characters (CJK Unified Ideographs)
        import re
        chinese_chars = re.findall(r'[\u4e00-\u9fff\u3000-\u303f\uff00-\uffef]', s[:500])
        if chinese_chars:
            print(f"Encoding '{enc}' decodes correctly and contains {len(chinese_chars)} Chinese chars:")
            print(s[:200])
            print("---")
    except:
        pass

# Check raw bytes pattern around garbled area
print("Raw bytes around position 80-160:")
for i, b in enumerate(data[80:160]):
    print(f'{80+i:4d}: 0x{b:02x} ({b:3d}) {chr(b) if 32 <= b < 127 else "."}')
