import os

BASE = 'D:\\yingyong\\xampp\\htdocs\\hongdu'

for fname in ['news-detail.html', 'case-detail.html']:
    path = os.path.join(BASE, fname)
    with open(path, 'rb') as f:
        raw = f.read()
    text = raw.decode('utf-8')
    
    # Find the body opening tag
    body_idx = text.find('<body')
    if body_idx < 0:
        print(fname + ': no <body tag found')
        continue
    
    # Check if body already has inline style
    body_end = text.find('>', body_idx)
    body_opening = text[body_idx:body_end+1]
    
    if 'style=' in body_opening:
        # Add flex styles
        if 'display:flex' not in body_opening:
            # Append to existing style
            new_body = body_opening.replace('style="', 'style="display:flex;flex-direction:column;min-height:100vh;')
            text = text[:body_idx] + new_body + text[body_end+1:]
            print(fname + ': added flex to existing body style')
    else:
        # Add style attribute
        new_body = '<body style="display:flex;flex-direction:column;min-height:100vh"'
        text = text[:body_idx] + new_body + text[body_end+1:]
        print(fname + ': added inline style to body')
    
    # Find footer start and wrap content before it
    ft_start = text.find('<footer class=')
    if ft_start < 0:
        print(fname + ': no footer found')
        # Try without class
        ft_start = text.find('<footer>')
    
    if ft_start > 0:
        # Find the content between the header section and footer
        # Look for the closing tag of the page header section
        # In these files, the section before footer starts after style block ends
        style_end = text.find('</style>')
        if style_end > 0:
            # After style block, there's the article content
            # Wrap everything after the closing of the main header/container
            # and before the footer
            content_start = text.find('</header>', 0, ft_start)
            if content_start < 0:
                # No HTML5 header tag, look for end of main section
                # Just wrap right before footer
                before = text[:ft_start]
                after = text[ft_start:]
                text = before + '\n    </div>\n' + after
            else:
                # Wrap between </header> and <footer>
                header_end = text.index('</header>', 0, ft_start)
                before_header_close = text[:header_end + 9]
                after_header_close = text[header_end + 9:ft_start]
                footer_part = text[ft_start:]
                
                # Wrap content
                wrapped = '<div style="flex:1 0 auto">' + after_header_close + '</div>'
                text = before_header_close + wrapped + footer_part
            
            with open(path, 'wb') as f:
                f.write(text.encode('utf-8'))
            print(fname + ': sticky footer applied')
        else:
            print(fname + ': no </style> found')
    else:
        print(fname + ': no footer found at all')

print('Done!')
