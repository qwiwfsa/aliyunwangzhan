#!/usr/bin/env python3
import sys
sys.stdout.reconfigure(encoding='utf-8')
import re, os

base = 'D:/yingyong/xampp/htdocs/hongdu'

# Check all frontend HTML pages for logo references
print("=== Logo References in Frontend Pages ===")
for fname in sorted(os.listdir(base)):
    if not fname.endswith('.html') or fname in ['readme.html']:
        continue
    fpath = os.path.join(base, fname)
    with open(fpath, 'r', encoding='utf-8') as f:
        content = f.read()
    
    logo_refs = re.findall(r'<img[^>]*src="images/[^"]*logo[^"]*"[^>]*>', content, re.IGNORECASE)
    if logo_refs:
        for ref in logo_refs:
            print(f'  {fname}: {ref[:120]}')
    
    favicon = re.findall(r'<link[^>]*rel="(?:shortcut )?icon"[^>]*>', content, re.IGNORECASE)
    if favicon:
        for f in favicon:
            print(f'  {fname}: {f[:120]}')

# Also check admin pages
print("\n=== Logo References in Admin Pages ===")
admin_base = os.path.join(base, 'admin')
for root, dirs, files in os.walk(admin_base):
    for fname in files:
        if not fname.endswith('.html'):
            continue
        fpath = os.path.join(root, fname)
        try:
            with open(fpath, 'r', encoding='utf-8') as f:
                content = f.read()
        except:
            try:
                with open(fpath, 'r', encoding='latin-1') as f:
                    content = f.read()
            except:
                continue
        
        logo_refs = re.findall(r'<img[^>]*src="[^"]*logo[^"]*"[^>]*>', content, re.IGNORECASE)
        if logo_refs:
            for ref in logo_refs:
                rel_path = os.path.relpath(fpath, base)
                print(f'  {rel_path}: {ref[:120]}')

# Check existing logo files
print("\n=== Existing Logo Files ===")
images_dir = os.path.join(base, 'images')
for f in sorted(os.listdir(images_dir)):
    if 'logo' in f.lower():
        fpath = os.path.join(images_dir, f)
        size = os.path.getsize(fpath)
        print(f'  {f} ({size:,} bytes)')

# Check how header/footer is structured 
print("\n=== Header Structure ===")
head_path = os.path.join(base, 'index.html')
with open(head_path, 'r', encoding='utf-8') as f:
    content = f.read()

# Find header area
header_match = re.search(r'<header[^>]*>(.*?)</header>', content, re.DOTALL | re.IGNORECASE)
if header_match:
    header_html = header_match.group(0)
    print(f'Header found, length: {len(header_html)} chars')
    # Find the logo part
    logo_tag = re.search(r'<a[^>]*class="logo"[^>]*>.*?</a>', header_html, re.DOTALL)
    if logo_tag:
        print(f'Logo tag: {logo_tag.group()[:200]}')

# Find footer area
footer_match = re.search(r'<footer[^>]*>(.*?)</footer>', content, re.DOTALL | re.IGNORECASE)
if footer_match:
    footer_html = footer_match.group(0)
    print(f'\nFooter found, length: {len(footer_html)} chars')
    footer_logo = re.search(r'<a[^>]*class="footer-logo"[^>]*>.*?</a>', footer_html, re.DOTALL)
    if footer_logo:
        print(f'Footer logo: {footer_logo.group()[:200]}')
