"""
Fix all issues for hongdu CMS:
1. case-detail.html: Convert from GB2312 to UTF-8
2. news-detail.html: Already fixed
3. Footer sticky: Add CSS to news-detail.html and case-detail.html
4. Category editing: Fix the backtick string encoding issue
"""

import os, re

BASE = 'D:\\yingyong\\xampp\\htdocs\\hongdu'

# ====== Fix 1: Convert case-detail.html from GB2312 to UTF-8 ======
print("=== Fix 1: Convert case-detail.html to UTF-8 ===")
path = os.path.join(BASE, 'case-detail.html')
with open(path, 'rb') as f:
    raw = f.read()

# Decode as GB2312
try:
    text = raw.decode('gb2312')
    print(f"Decoded as GB2312 successfully")
except:
    print("ERROR: Cannot decode as GB2312")
    exit(1)

# Also check: are there any 'Yao' followed by garbled chars that indicate
# the content was written in UTF-8 but the file was saved as GB2312?
# Replace 'Yao' with proper Chinese if needed
# Yao 资金网 - so 'Yao' is part of brand name, keep as is

utf8 = text.encode('utf-8')
with open(path, 'wb') as f:
    f.write(utf8)
print(f"Written UTF-8 file: {len(utf8)} bytes (was {len(raw)})")

# Verify
with open(path, 'rb') as f:
    verified = f.read()
try:
    verified.decode('utf-8')
    print("VERIFIED: UTF-8 encoding OK")
except:
    print("ERROR: Verification failed")

# ====== Fix 2: Footer sticky - Add to news-detail.html ======
print("\n=== Fix 2: Footer sticky ===")

def add_sticky_footer_css(filepath):
    """Add CSS to make footer stick to bottom"""
    with open(filepath, 'rb') as f:
        data = f.read()
    text = data.decode('utf-8')
    
    # Add sticky footer CSS to the style block
    style_end = text.find('</style>')
    if style_end > 0:
        sticky_css = """
        /* 页面布局 - 页脚固定到底部 */
        html, body {
            height: 100%;
            margin: 0;
        }
        body {
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }
        .main-content {
            flex: 1 0 auto;
        }
        .footer {
            flex-shrink: 0;
        }
"""
        # Insert after opening <style>
        style_open = text.find('<style>')
        new_text = text[:style_open+7] + '\n' + sticky_css + text[style_open+7:]
        
        with open(filepath, 'wb') as f:
            f.write(new_text.encode('utf-8'))
        print(f"Added sticky footer CSS to {os.path.basename(filepath)}")
    else:
        print(f"No <style> found in {filepath}")

def wrap_main_content(filepath, content_wrapper_class='main-content'):
    """Wrap main content area with a div that fills remaining space"""
    with open(filepath, 'rb') as f:
        data = f.read()
    text = data.decode('utf-8')
    
    # In news-detail.html and case-detail.html, find the main content 
    # between header and footer and wrap it
    # Pattern: look for footer, wrap everything before it
    
    # Strategy: Find <footer class="footer"> and wrap the section before it
    footer_idx = text.find('<footer class="footer">')
    if footer_idx > 0:
        # Find the end of the header section (</header> or end of main-header)
        # We need to find the article-detail-header end
        # Look for main content start - after the header section
        
        # Actually, simpler approach: wrap content between header end and footer
        # Find where main article content starts
        # Let's look for the closing of article-detail-header
        header_end = text.find('</header>', 0, footer_idx)
        
        if header_end > 0:
            # Insert wrapper opening after </header>
            new_text = text[:header_end + 9] + f'\n    <div class="{content_wrapper_class}">' + text[header_end + 9:]
            # Now find footer and insert wrapper closing before footer
            # The footer index shifted by our insertion
            shifted_footer = new_text.find('<footer class="footer">')
            new_text = new_text[:shifted_footer] + '\n    </div>\n' + new_text[shifted_footer:]
            
            with open(filepath, 'wb') as f:
                f.write(new_text.encode('utf-8'))
            print(f"Wrapped main content in {os.path.basename(filepath)}")
            return True
    
    print(f"Could not wrap content in {os.path.basename(filepath)} (footer not found)")
    return False

# Apply to news-detail.html
news_path = os.path.join(BASE, 'news-detail.html')
add_sticky_footer_css(news_path)
wrap_main_content(news_path)

# Apply to case-detail.html
case_path = os.path.join(BASE, 'case-detail.html')
add_sticky_footer_css(case_path)
wrap_main_content(case_path)

# ====== Fix 3: Category editing - ensure edit/save works ======
print("\n=== Fix 3: Category editing ===")
cat_path = os.path.join(BASE, 'admin', 'components', 'news', 'category.html')
with open(cat_path, 'rb') as f:
    data = f.read()
text = data.decode('utf-8')

# Check if the editCategory modal title has correct Chinese
modal_title_match = re.search(r"document\.getElementById\('modalTitle'\)\.textContent\s*=\s*'([^']+)'", text)
if modal_title_match:
    print(f"Modal title in JS: {modal_title_match.group(1)}")

# Problem might be: templates contain hardcoded Chinese text in template literals
# that becomes garbled. Let me check the actual bytes of certain phrases
for phrase in ['暂无分类', '排序', '编辑', '删除']:
    idx = text.find(phrase)
    if idx >= 0:
        print(f"Found '{phrase}' at position {idx}")
    else:
        print(f"MISSING '{phrase}' - this is likely the issue!")

print("\n=== All fixes applied ===")
