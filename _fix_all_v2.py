"""
Fix encoding: The original files were GBK. 六六 edited them, writing SEO scripts
as UTF-8, but the original GBK Chinese bytes remained. Browsers read them as 
UTF-8 (per meta charset) -> garbled.

Strategy:
1. For files that can be decoded as pure GBK: decode GBK -> save as UTF-8
2. For files with mixed GBK+UTF-8 bytes: decode GBK leniently -> save as UTF-8
"""
import os, re

files = ['index.html', 'services.html', 'cases.html', 'case-detail.html', 'case-edit.html',
         'contact.html', 'news.html', 'news-detail.html', 'faq.html', 'faq_new.html',
         'advantages.html', 'applications.html', 'compliance.html', 'privacy.html',
         'sitemap.html', 'readme.html', 'test-api.html', 'test-news-load.html']

fixed = 0
failed = 0

for f in files:
    if not os.path.exists(f):
        print(f'SKIP {f}: NOT FOUND')
        continue
    
    with open(f, 'rb') as fp:
        raw = fp.read()
    
    # Create backup with .bak extension
    bak_name = f + '.gbk.bak'
    with open(bak_name, 'wb') as fp:
        fp.write(raw)
    print(f'BACKUP {f} -> {bak_name}')
    
    # Decode as GBK (lenient for mixed files)
    text = raw.decode('gbk', errors='replace')
    
    # Check for replacement chars - if none, it was pure GBK
    replaced_count = text.count('\ufffd')
    print(f'  {f}: {len(raw)} bytes, {replaced_count} replacement chars in GBK decode')
    
    # Find charset meta tag and keep it as UTF-8
    # (the meta charset is already UTF-8 in current files, just need to fix the actual encoding)
    meta_m = re.search(r'<meta[^>]*charset=["\']?(\S+?)["\'>\s]', text[:2000], re.I)
    if meta_m:
        print(f'  Meta charset currently: {meta_m.group(1)}')
    
    # Write as UTF-8 with proper encoding
    utf8_bytes = text.encode('utf-8')
    with open(f, 'wb') as fp:
        fp.write(utf8_bytes)
    
    print(f'  Saved as UTF-8 ({len(utf8_bytes)} bytes)')
    fixed += 1

print(f'\n=== FIX SUMMARY ===')
print(f'Total files processed: {fixed}')

# VERIFICATION
print(f'\n=== VERIFICATION ===')
for f in files:
    if not os.path.exists(f):
        continue
    with open(f, 'rb') as fp:
        raw = fp.read()
    try:
        text = raw.decode('utf-8')
        chinese_chars = [c for c in text if ord(c) > 0x4e00]
        print(f'{f}: UTF-8 OK, chinese_chars={len(chinese_chars)}')
        
        # Check if the Chinese makes sense (contains known words)
        sample = ''.join(chinese_chars[:30])
        print(f'  Sample: {sample}')
        
        # Check SEO script is preserved
        if 'seo' in text.lower():
            script_idx = text.lower().rfind('<script')
            head_idx = text.lower().rfind('</head>')
            if script_idx > 0 and head_idx > 0 and abs(head_idx - script_idx) < 2000:
                print(f'  SEO script preserved (near </head>)')
        
        # Verify meta charset
        meta = re.search(r'charset=["\']?(\S+?)["\'>\s]', text[:2000], re.I)
        if meta:
            print(f'  Meta charset: {meta.group(1)}')
            
    except Exception as e:
        print(f'{f}: VERIFY FAILED: {e}')
