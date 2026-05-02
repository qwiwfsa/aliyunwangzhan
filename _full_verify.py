import os, re

files = ['index.html', 'services.html', 'cases.html', 'case-detail.html', 'case-edit.html',
         'contact.html', 'news.html', 'news-detail.html', 'faq.html', 'faq_new.html',
         'advantages.html', 'applications.html', 'compliance.html', 'privacy.html',
         'sitemap.html', 'readme.html', 'test-api.html', 'test-news-load.html']

all_ok = True
for f in files:
    if not os.path.exists(f):
        print(f'ERROR: {f} not found!')
        all_ok = False
        continue
    
    with open(f, 'rb') as fp:
        raw = fp.read()
    
    # Check encoding is valid UTF-8 without BOM
    if raw[:3] == bytes([0xef, 0xbb, 0xbf]):
        print(f'WARN: {f} has UTF-8 BOM')
    
    try:
        text = raw.decode('utf-8')
    except Exception as e:
        print(f'ERROR: {f} is not valid UTF-8: {e}')
        all_ok = False
        continue
    
    # Check for Chinese characters
    chinese_chars = [c for c in text if ord(c) > 0x4e00]
    if not chinese_chars:
        print(f'OK: {f} - no Chinese content (expected for this file)')
        continue
    
    # Check for any replacement characters (garbled text indicator)
    if '\ufffd' in text:
        print(f'WARN: {f} has {text.count(chr(0xfffd))} replacement chars!')
        all_ok = False
    
    # Check SEO script
    has_seo_script = False
    if 'fetch-seo.php' in text:
        has_seo_script = True
    
    # Check meta charset
    meta = re.search(r'charset=["\']?([^"\'\\s>]+)', text[:500], re.I)
    
    print(f'OK: {f} - {len(chinese_chars)} Chinese chars, SEO={has_seo_script}, charset={meta.group(1) if meta else "UNKNOWN"}')
    
    # Show first line of Chinese text (meta description or title)
    desc = re.search(r'content="([^"]*[\u4e00-\u9fff][^"]*)"', text[:3000])
    if desc:
        print(f'  Sample: {desc.group(1)[:60]}...')

print(f'\n=== FINAL RESULT ===')
if all_ok:
    print('ALL FILES FIXED SUCCESSFULLY!')
else:
    print('SOME FILES HAVE ISSUES')
