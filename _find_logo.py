import os, re

html_dir = r'D:\yingyong\xampp\htdocs\hongdu'
patterns = [
    '宏都资本',
    'logo-icon',
    'class="logo"',
    'footer-logo',
]

for root, dirs, files in os.walk(html_dir):
    # Skip wp-* directories and backup directories
    if any(skip in root for skip in ['\\wp-', '\\backup', '\\node_modules']):
        continue
    for f in files:
        if f.endswith('.html'):
            path = os.path.join(root, f)
            with open(path, 'r', encoding='utf-8', errors='ignore') as fh:
                content = fh.read()
            rel = os.path.relpath(path, html_dir)
            finds = []
            for p in patterns:
                idx = content.find(p)
                if idx >= 0:
                    # Show context around first occurrence
                    start = max(0, idx-50)
                    end = min(len(content), idx+100)
                    snippet = content[start:end].replace('\n', '\\n')
                    finds.append(f"  [{p}]: ...{snippet}...")
            if finds:
                print(f"\n=== {rel} ===")
                for line in finds:
                    print(line)
