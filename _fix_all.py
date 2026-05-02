"""Fix encoding: decode current files as GBK (original), save as UTF-8 (correct).
Current files have GBK-encoded Chinese bytes but meta charset says UTF-8.
This fix reads them as GBK, then writes them as proper UTF-8.
"""
import os

files = ['index.html', 'services.html', 'cases.html', 'case-detail.html', 'case-edit.html',
         'contact.html', 'news.html', 'news-detail.html', 'faq.html', 'faq_new.html',
         'advantages.html', 'applications.html', 'compliance.html', 'privacy.html',
         'sitemap.html', 'readme.html', 'test-api.html', 'test-news-load.html']

fixed = 0
failed = 0
skipped = 0

for f in files:
    if not os.path.exists(f):
        print(f'SKIP {f}: NOT FOUND')
        skipped += 1
        continue
    
    with open(f, 'rb') as fp:
        raw = fp.read()
    
    # Backup the original
    bak_name = f + '.bak'
    with open(bak_name, 'wb') as fp:
        fp.write(raw)
    print(f'BACKUP {f} -> {bak_name} ({len(raw)} bytes)')
    
    # Try GBK decode
    try:
        text = raw.decode('gbk')
        print(f'  Decoded GBK OK, {len(text)} chars')
    except Exception as e:
        print(f'  FAIL: GBK decode error: {e}')
        # For mixed encoding files, try lenient decode
        try:
            text = raw.decode('gbk', errors='replace')
            print(f'  Decoded GBK (lenient) OK, {len(text)} chars')
        except:
            print(f'  SKIP: cannot decode at all')
            failed += 1
            continue
    
    # Write as UTF-8 (without BOM to match current format)
    try:
        utf8_bytes = text.encode('utf-8')
        with open(f, 'wb') as fp:
            fp.write(utf8_bytes)
        print(f'  SAVED as UTF-8 ({len(utf8_bytes)} bytes)')
        fixed += 1
    except Exception as e:
        print(f'  FAIL: could not save: {e}')
        failed += 1

print(f'\n=== SUMMARY ===')
print(f'Fixed: {fixed}, Failed: {failed}, Skipped: {skipped}')

# Now verify by re-reading fixed files
print(f'\n=== VERIFICATION ===')
for f in ['index.html', 'services.html', 'contact.html', 'test-api.html', 'test-news-load.html']:
    if not os.path.exists(f):
        continue
    with open(f, 'rb') as fp:
        raw = fp.read()
    try:
        text = raw.decode('utf-8')
        chinese_chars = [c for c in text if ord(c) > 0x4e00]
        print(f'{f}: UTF-8 decode OK, chinese_chars={len(chinese_chars)}')
        import re
        meta = re.search(r'charset=["\']?(\S+?)["\'>\s]', text[:2000], re.I)
        if meta:
            print(f'  Meta charset: {meta.group(1)}')
        # Check SEO script
        if 'seo' in text.lower():
            seo_idx = text.lower().rfind('<script')
            head_idx = text.lower().rfind('</head>')
            if seo_idx > head_idx - 2000:
                print(f'  SEO script preserved')
        if chinese_chars:
            sample = ''.join(chinese_chars[:20])
            print(f'  Chinese sample: {sample}')
    except Exception as e:
        print(f'{f}: UTF-8 decode FAILED: {e}')
