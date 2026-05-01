import re

# Check advantages.html
with open(r'D:\yingyong\xampp\htdocs\hongdu\advantages.html', 'r', encoding='utf-8', errors='ignore') as f:
    c = f.read()

# Find the logo section
idx = c.find('class="logo"')
if idx >= 0:
    snippet = c[idx:idx+300]
    print("=== advantages.html logo section ===")
    print(repr(snippet))
    print()

# Check the regex
pattern = r'(<a[^>]*class="logo"[^>]*>)\s*<div class="logo-icon">\s*<i class="fas fa-building"[^>]*>\s*</i>\s*</div>\s*<span>\u5b8f\u90fd\u8d44\u672c</span>\s*</a>'
m = re.search(pattern, c)
if m:
    print("MATCHED:", m.group(0)[:100])
else:
    print("NOT MATCHED by regex")
    # Show the relevant part
    idx = c.find('class="logo"')
    if idx >= 0:
        # Show up to </a>
        end = c.find('</a>', idx)
        if end >= 0:
            logo_section = c[idx:end+4]
            print("ACTUAL text:")
            print(repr(logo_section))
