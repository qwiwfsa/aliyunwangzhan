import re, os

base = r'D:\yingyong\xampp\htdocs\hongdu\admin'
files = ['index.html', 'nav-management.html', 'faq-management.html', 'case-edit.html', 'faq-edit.html']

for fname in files:
    path = os.path.join(base, fname)
    with open(path, 'r', encoding='utf-8') as f:
        c = f.read()
    
    # Find nav sections
    m = re.search(r'<nav class="cms-sidebar-nav">', c)
    if not m:
        print(f'=== {fname} - NAV NOT FOUND ===')
        continue
    
    start = m.start()
    end = c.find('</nav>', start) + 6
    print(f'=== {fname} (chars {start}-{end}) ===')
    print(c[start:end])
    print()
