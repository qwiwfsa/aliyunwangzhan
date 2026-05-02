import os

files = ['index.html', 'services.html', 'cases.html', 'case-detail.html', 'case-edit.html',
         'contact.html', 'news.html', 'news-detail.html', 'faq.html', 'faq_new.html',
         'advantages.html', 'applications.html', 'compliance.html', 'privacy.html',
         'sitemap.html', 'readme.html', 'test-api.html', 'test-news-load.html']

print("=== DECODING CURRENT FILES AS GBK AND CHECKING RESULT ===")
for f in files:
    if not os.path.exists(f):
        print(f'{f}: NOT FOUND')
        continue
    with open(f, 'rb') as fp:
        raw = fp.read()
    
    # Try GBK first
    try:
        gbk_text = raw.decode('gbk')
        # Verify Chinese characters make sense
        chinese_chars = [c for c in gbk_text if ord(c) > 0x4e00]
        print(f'{f}: GBK decode OK, chinese_chars={len(chinese_chars)}')
        if chinese_chars:
            sample = ''.join(chinese_chars[:30])
            print(f'  Sample Chinese: {sample}')
        # Check for meta charset
        import re
        m = re.search(r'charset=["\']?(\S+?)["\'>\s]', gbk_text[:2000], re.I)
        if m:
            print(f'  Meta charset: {m.group(1)}')
        # Check for SEO script
        if 'seo' in gbk_text.lower():
            idx = gbk_text.lower().rfind('<script')
            if idx > gbk_text.lower().rfind('</head>') - 2000:
                print('  SEO script found near </head>')
    except Exception as e:
        print(f'{f}: GBK decode FAILED - {e}')
        # Try other decodings
        try:
            utf8_text = raw.decode('utf-8')
            chinese_chars = [c for c in utf8_text if ord(c) > 0x4e00]
            print(f'  UTF-8 decode OK, chinese_chars={len(chinese_chars)}')
            if chinese_chars:
                sample = ''.join(chinese_chars[:30])
                print(f'  Sample Chinese: {sample}')
        except:
            print(f'  UTF-8 also FAILED')
    print()
