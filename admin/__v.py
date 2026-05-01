import re

files = {
    'index.html': r'D:\yingyong\xampp\htdocs\hongdu\admin\index.html',
    'nav-management.html': r'D:\yingyong\xampp\htdocs\hongdu\admin\nav-management.html',
    'faq-management.html': r'D:\yingyong\xampp\htdocs\hongdu\admin\faq-management.html',
    'case-edit.html': r'D:\yingyong\xampp\htdocs\hongdu\admin\case-edit.html',
    'faq-edit.html': r'D:\yingyong\xampp\htdocs\hongdu\admin\faq-edit.html'
}
for name, path in files.items():
    with open(path, 'r', encoding='utf-8') as f:
        content = f.read()
    m = re.search(r'<nav class="cms-sidebar-nav">(.*?)</nav>', content, re.DOTALL)
    if m:
        nav = m.group(1)
        sections = re.split(r'(?=<div class="cms-nav-section">)', nav)
        print(f'=== {name} ===')
        for sec in sections:
            if 'cms-nav-section' in sec:
                t = ''
                tm = re.search(r'cms-nav-title">([^<]+)</div>', sec)
                if tm:
                    t = tm.group(1)
                print(f'  [{t}]')
                items = re.findall(r'<a href="([^"]+)"[^>]*>.*?<span>([^<]+)</span>\s*</a>', sec)
                for href, label in items:
                    active = ''
                    if 'active' in content[content.find('<a href="'+href+'"'):content.find('</a>', content.find('<a href="'+href+'"'))+4]:
                        active = ' [ACTIVE]'
                    print(f'    - {label} -> {href}{active}')
        print()
