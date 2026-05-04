import re
import os

mobile_dir = 'mobile'
files = [
    'advantages.html',
    'cases.html',
    'contact.html',
    'faq.html',
    'index.html',
    'news-detail.html',
    'news.html',
    'services.html'
]

for filename in files:
    filepath = os.path.join(mobile_dir, filename)

    try:
        with open(filepath, 'r', encoding='utf-8') as f:
            content = f.read()

        # Find and replace footer section - keep only copyright and disclaimer
        footer_pattern = r'(<footer class="footer">.*?<div class="footer-container">)(.*?)(<div class="footer-bottom">.*?</div>\s*</div>\s*</footer>)'

        def replace_footer(match):
            footer_start = match.group(1)
            footer_end = match.group(3)
            # Return footer with only the bottom section (copyright and disclaimer)
            return footer_start + '\n            ' + footer_end

        content = re.sub(footer_pattern, replace_footer, content, flags=re.DOTALL)

        with open(filepath, 'w', encoding='utf-8') as f:
            f.write(content)

        print(f"Processed: {filename}")
    except Exception as e:
        print(f"Error processing {filename}: {e}")

print("Mobile footer simplification complete")
