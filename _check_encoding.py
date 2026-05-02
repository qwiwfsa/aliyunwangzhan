import os, re

files = ['index.html', 'services.html', 'cases.html', 'case-detail.html', 'case-edit.html',
         'contact.html', 'news.html', 'news-detail.html', 'faq.html', 'faq_new.html',
         'advantages.html', 'applications.html', 'compliance.html', 'privacy.html',
         'sitemap.html', 'readme.html', 'test-api.html', 'test-news-load.html']

print("=== CHECKING CURRENT FILES ===")
for f in files:
    if not os.path.exists(f):
        print(f'{f}: NOT FOUND')
        continue
    with open(f, 'rb') as fp:
        data = fp.read()
    
    # Try GBK
    try:
        decoded_gbk = data.decode('gbk')
        has_chinese = any(ord(c) > 0x4e00 for c in decoded_gbk)
        print(f'{f}: GBK decode OK, has_chinese={has_chinese}, size={len(data)}')
        # Find charset meta
        m = re.search(r'<meta[^>]*charset=(\S+)', decoded_gbk[:2000], re.I)
        if m:
            print(f'  Meta charset: {m.group(1)}')
        # Find some Chinese text
        chinese_chars = [c for c in decoded_gbk if ord(c) > 0x4e00]
        if chinese_chars:
            print(f'  Sample Chinese (first 20): {''.join(chinese_chars[:20])}')
        # Check for replacement chars (mojibake indicators)
        replacement_marks = decoded_gbk.count('\ufffd')
        print(f'  Replacement chars (\\ufffd): {replacement_marks}')
    except Exception as e:
        print(f'{f}: GBK decode FAILED: {e}')
    print()

print()
print("=== CHECKING BACKUP FILES (backup_20260502) ===")
backup_files = ['advantages.html', 'applications.html', 'case-detail.html', 'cases.html',
                'compliance.html', 'contact.html', 'faq.html', 'index.html',
                'news-detail.html', 'news.html', 'privacy.html', 'services.html', 'sitemap.html']
for f in backup_files:
    path = os.path.join('backup_20260502', f)
    if not os.path.exists(path):
        print(f'backup_20260502/{f}: NOT FOUND')
        continue
    with open(path, 'rb') as fp:
        data = fp.read()
    try:
        decoded = data.decode('gbk')
        has_chinese = any(ord(c) > 0x4e00 for c in decoded)
        print(f'backup_20260502/{f}: GBK decode OK, has_chinese={has_chinese}, size={len(data)}')
        m = re.search(r'<meta[^>]*charset=(\S+)', decoded[:2000], re.I)
        if m:
            print(f'  Meta charset: {m.group(1)}')
        # Check for replacement chars
        replacement_marks = decoded.count('\ufffd')
        print(f'  Replacement chars: {replacement_marks}')
    except Exception as e:
        print(f'backup_20260502/{f}: GBK decode FAILED: {e}')
    print()
