import codecs

with open('D:\\yingyong\\xampp\\htdocs\\hongdu\\news-detail.html', 'rb') as f:
    data = f.read()

# Find the first description content area
idx = data.find(b'description')
if idx > 0:
    # Find the content after content=
    content_start = data.find(b'content="', idx)
    if content_start > 0:
        end_quote = data.find(b'"', content_start + 9)
        content_bytes = data[content_start + 9:end_quote]
        print(f"Raw GB2312 bytes at description: {content_bytes}")
        
        # Try to decode as GB2312
        try:
            decoded = content_bytes.decode('gb2312')
            print(f"Decoded as GB2312: {decoded}")
        except:
            print("Cannot decode as GB2312")
        
        # Also try as UTF-8
        try:
            decoded = content_bytes.decode('utf-8')
            print(f"Decoded as UTF-8: {decoded}")
        except:
            print("Cannot decode as UTF-8")

# Find title area
idx = data.find(b'<title>')
if idx > 0:
    end = data.find(b'</title>', idx)
    title_bytes = data[idx+7:end]
    print(f"\nTitle bytes: {title_bytes}")
    try:
        print(f"Decoded as GB2312: {title_bytes.decode('gb2312')}")
    except:
        print("Cannot decode title as GB2312")

# Check if the file is UTF-8 with BOM or GB2312/GBK
print(f"\nFile size: {len(data)} bytes")
print(f"First bytes: {data[:10].hex()}")

# Check BOM
if data[:3] == b'\xef\xbb\xbf':
    print("Has UTF-8 BOM")
elif data[:2] == b'\xff\xfe':
    print("Has UTF-16 LE BOM")
else:
    print("No BOM detected")

# Full decode as GB2312
full_gb = data.decode('gb2312', errors='replace')
print(f"\nFirst 200 chars decoded as GB2312:")
print(full_gb[:200])
