import os, re

html_dir = r'D:\yingyong\xampp\htdocs\hongdu'

# Focus on root html files only (these are the main website pages)
root_files = [f for f in os.listdir(html_dir) if f.endswith('.html') and os.path.isfile(os.path.join(html_dir, f))]

for fname in sorted(root_files):
    fp = os.path.join(html_dir, fname)
    with open(fp, 'r', encoding='utf-8', errors='ignore') as f:
        c = f.read()
    
    has_old_logo = '<i class="fas fa-building"' in c and ('宏都资本' in c[c.find('<i class="fas fa-building"'):c.find('<i class="fas fa-building"')+500])
    has_img_logo = 'images/logo.png' in c and 'class="logo"' in c
    has_icon_logo = 'class="logo-icon"' in c
    has_yoo_text = 'Yoo资金网' in c
    
    status = []
    if has_img_logo: status.append('IMG')
    if has_icon_logo: status.append('ICON')
    if has_yoo_text: status.append('Yoo')
    
    print(f"{fname:25s} {'|'.join(status):20s} {'(has old 宏都资本 logo icon)' if has_old_logo else ''}")
