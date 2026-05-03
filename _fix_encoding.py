# Convert news-detail.html from GB2312 to UTF-8
import codecs

input_path = 'D:\\yingyong\\xampp\\htdocs\\hongdu\\news-detail.html'

with open(input_path, 'rb') as f:
    data = f.read()

# Decode as GB2312, re-encode as UTF-8
text = data.decode('gb2312')
utf8_data = text.encode('utf-8')

with open(input_path, 'wb') as f:
    f.write(utf8_data)

print(f"Converted {len(data)} bytes from GB2312 to UTF-8 ({len(utf8_data)} bytes)")
print("Verification:")
with open(input_path, 'rb') as f:
    verified = f.read()
    
print(f"File size: {len(verified)} bytes")
print(f"First 300 chars:")
print(verified[:300].decode('utf-8'))
