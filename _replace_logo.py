import os, re

html_dir = r'D:\yingyong\xampp\htdocs\hongdu'

# Patterns to replace
# Navbar logo: <a ... class="logo" ...><div class="logo-icon"><i class="fas fa-building"></i></div><span>宏都资本</span></a>
# Footer logo: <a ... class="footer-logo"><div class="footer-logo-icon"><i class="fas fa-building"></i></div><span>宏都资本</span></a>

logo_img_tag = '<img src="images/logo.png" alt="Yoo\u8d44\u91d1\u7f51" style="width:221px;height:64px;">'

# These are the specific pattern replacements
navbar_logo_pattern = r'(<a[^>]*class="logo"[^>]*>)\s*<div class="logo-icon">\s*<i class="fas fa-building"[^>]*>\s*</i>\s*</div>\s*<span>宏都资本</span>\s*</a>'
footer_logo_pattern = r'(<a[^>]*class="footer-logo"[^>]*>)\s*<div class="footer-logo-icon">\s*<i class="fas fa-building"[^>]*>\s*</i>\s*</div>\s*<span>宏都资本</span>\s*</a>'

navbar_replacement = r'\1' + logo_img_tag + r'</a>'
footer_replacement = r'\1' + logo_img_tag + r'</a>'

# Get all HTML files in root and admin
files_to_process = []
for f in os.listdir(html_dir):
    if f.endswith('.html') and os.path.isfile(os.path.join(html_dir, f)):
        files_to_process.append(os.path.join(html_dir, f))

admin_dir = os.path.join(html_dir, 'admin')
if os.path.isdir(admin_dir):
    for root, dirs, fs in os.walk(admin_dir):
        # Skip backup directories
        if 'backup' in root.lower():
            continue
        for f in fs:
            if f.endswith('.html'):
                files_to_process.append(os.path.join(root, f))

total_navbar = 0
total_footer = 0
total_other = 0
processed_files = []

for filepath in files_to_process:
    relpath = os.path.relpath(filepath, html_dir)
    try:
        with open(filepath, 'r', encoding='utf-8', errors='ignore') as f:
            content = f.read()
        
        original = content
        
        # Replace navbar logo pattern
        content = re.sub(navbar_logo_pattern, navbar_replacement, content)
        
        # Replace footer logo pattern
        content = re.sub(footer_logo_pattern, footer_replacement, content)
        
        # Also replace any remaining standalone <span>宏都资本</span> in logo/header context
        # But be careful not to replace meta descriptions etc.
        
        navbar_count = len(re.findall(navbar_logo_pattern, original))
        footer_count = len(re.findall(footer_logo_pattern, original))
        
        if content != original:
            with open(filepath, 'w', encoding='utf-8') as f:
                f.write(content)
            total_navbar += navbar_count
            total_footer += footer_count
            processed_files.append(f"{relpath} (navbar:{navbar_count}, footer:{footer_count})")
            print(f"UPDATED: {relpath}")
        else:
            # Check if it already has the img logo
            if 'images/logo.png' in content and 'class="logo"' in content:
                print(f"SKIP (already has img logo): {relpath}")
            elif 'Yoo资金网' in content and 'logo-icon' not in content:
                print(f"SKIP (already has Yoo logo): {relpath}")
        
    except Exception as e:
        print(f"ERROR {relpath}: {e}")

print(f"\n\n=== SUMMARY ===")
print(f"Files processed: {len(files_to_process)}")
print(f"Files updated: {len(processed_files)}")
print(f"Navbar logos replaced: {total_navbar}")
print(f"Footer logos replaced: {total_footer}")
print(f"\nUpdated files:")
for f in processed_files:
    print(f"  - {f}")
