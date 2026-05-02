import os

backup_files = ['advantages.html', 'applications.html', 'case-detail.html', 'cases.html',
                'compliance.html', 'contact.html', 'faq.html', 'index.html',
                'news-detail.html', 'news.html', 'privacy.html', 'services.html', 'sitemap.html']
print("=== CHECKING BACKUP (backup_20260502) BYTES ===")
for f in backup_files:
    path = os.path.join('backup_20260502', f)
    if not os.path.exists(path):
        continue
    with open(path, 'rb') as fp:
        data = fp.read()
    print(f'{f}: size={len(data)}, first_bytes={data[:3].hex()}, has_bom={data[:3] == bytes([0xef,0xbb,0xbf])}')
    # Try UTF-8
    try:
        decoded = data.decode('utf-8')
        has_chinese = any(ord(c) > 0x4e00 for c in decoded)
        print(f'  UTF-8: OK, has_chinese={has_chinese}')
        if has_chinese:
            chinese_chars = [c for c in decoded if ord(c) > 0x4e00]
            print(f'  Chinese sample: {"".join(chinese_chars[:30])}')
            # Check meta charset
            import re
            m = re.search(r'<meta[^>]*charset=(\S+)', decoded[:2000], re.I)
            if m:
                print(f'  Meta charset: {m.group(1)}')
            # Check for SEO script
            if 'seo' in decoded.lower() or 'dynamic' in decoded.lower():
                print(f'  Has SEO/dynamic script mentions')
    except Exception as e:
        print(f'  UTF-8: FAILED - {e}')
    print()

print("=== CHECKING OLDER BACKUP (backup_20260427_205006) BYTES ===")
old_files = ['advantages.html', 'cases.html', 'contact.html', 'faq.html', 'index.html',
             'news.html', 'news-detail.html', 'services.html']
for f in old_files:
    path = os.path.join('backup_20260427_205006', f)
    if not os.path.exists(path):
        print(f'backup_20260427_205006/{f}: NOT FOUND')
        continue
    with open(path, 'rb') as fp:
        data = fp.read()
    print(f'{f}: size={len(data)}, first_bytes={data[:3].hex()}')
    try:
        decoded = data.decode('gbk')
        has_chinese = any(ord(c) > 0x4e00 for c in decoded)
        print(f'  GBK: OK, has_chinese={has_chinese}')
        if has_chinese:
            chinese_chars = [c for c in decoded if ord(c) > 0x4e00]
            print(f'  Chinese sample: {"".join(chinese_chars[:30])}')
            import re
            m = re.search(r'<meta[^>]*charset=(\S+)', decoded[:2000], re.I)
            if m:
                print(f'  Meta charset: {m.group(1)}')
            # Check for CSS/JS links
            css_links = re.findall(r'href=[\'\"]([^\'\"]*\.css)[\'\"]', decoded[:5000])
            if css_links:
                print(f'  CSS: {css_links[:3]}')
    except Exception as e:
        print(f'  GBK: FAILED - {e}')
    print()
