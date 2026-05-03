import os

BASE = 'D:\\yingyong\\xampp\\htdocs\\hongdu'

for fname in ['news-detail.html', 'case-detail.html']:
    path = os.path.join(BASE, fname)
    with open(path, 'rb') as f:
        raw = f.read()
    text = raw.decode('utf-8')
    
    # Find </header> and <footer class
    header_end = text.find('</header>')
    ft_start = text.find('<footer class=')
    
    if header_end > 0 and ft_start > header_end:
        # Split into parts
        before = text[:header_end + 9]
        content = text[header_end + 9:ft_start]
        after = text[ft_start:]
        
        # Wrap content with flex-growing wrapper
        wrapper = '<div id="content-wrapper" style="flex:1 0 auto">' + content + '</div>'
        new_text = before + wrapper + after
        
        # Fix body to use flex layout
        body_tag = new_text.find('<body')
        body_close = new_text.find('>', body_tag)
        body_attrs = new_text[body_tag:body_close+1]
        
        # Add style to make body flex
        old_body = '<body'
        new_body = '<body style="display:flex;flex-direction:column;min-height:100vh"'
        new_text = new_text.replace(old_body, new_body, 1)
        
        with open(path, 'wb') as f:
            f.write(new_text.encode('utf-8'))
        
        print(f"{fname}: Fixed footer sticky")
        print(f"  header_end={header_end}, ft_start={ft_start}")
        print(f"  content length={len(content)}")
    else:
        print(f"{fname}: Could not find structure (header_end={header_end}, ft_start={ft_start})")

print("Done!")
