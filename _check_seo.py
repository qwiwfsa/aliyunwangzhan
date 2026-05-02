import os, re

# First, check the current files for the SEO script to understand what it does
print("=== CURRENT FILES: Checking for SEO scripts ===")
current_files = ['index.html', 'services.html', 'cases.html', 'contact.html', 'faq.html']
for f in current_files:
    with open(f, 'rb') as fp:
        data = fp.read()
    try:
        decoded = data.decode('gbk')
    except:
        try:
            decoded = data.decode('utf-8')
        except:
            print(f'{f}: cannot decode')
            continue
    # Find script tags near /head
    head_end = decoded.find('</head>')
    if head_end > 0:
        scripts_before_head = decoded[head_end-1000:head_end]
        print(f'{f}: last 800 chars before </head>')
        print(f'  {scripts_before_head[-600:]}')
        print()
    # Also check for meta description/keywords
    for tag in ['meta name="description"', 'meta name="keywords"', 'meta name="robots"']:
        if tag in decoded.lower():
            idx = decoded.lower().find(tag)
            print(f'  Found {tag}: {decoded[idx:idx+200]}')

print()

# Now check the backup
print("=== BACKUP FILES: head section ===")
backup_files = ['index.html', 'services.html', 'cases.html', 'contact.html', 'faq.html']
for f in backup_files:
    path = os.path.join('backup_20260502', f)
    if not os.path.exists(path):
        continue
    with open(path, 'rb') as fp:
        data = fp.read()
    # Skip BOM
    if data[:3] == bytes([0xef, 0xbb, 0xbf]):
        data = data[3:]
    try:
        decoded = data.decode('utf-8')
    except:
        print(f'backup_20260502/{f}: cannot decode')
        continue
    head_end = decoded.find('</head>')
    if head_end > 0:
        scripts_before_head = decoded[head_end-800:head_end]
        print(f'backup_20260502/{f}: last 500 chars before </head>')
        print(f'  {scripts_before_head[-500:]}')
        print()
