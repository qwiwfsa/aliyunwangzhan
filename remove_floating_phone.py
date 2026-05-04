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

        # Remove chat-widget div block
        content = re.sub(
            r'<div class="chat-widget"[^>]*>.*?</div>\s*<div class="chat-widget-phone-display">.*?</div>',
            '',
            content,
            flags=re.DOTALL
        )

        # Also try to remove if structured differently
        content = re.sub(
            r'<div class="chat-widget"[^>]*>.*?</div>(?:\s*<div class="chat-widget-phone-display">.*?</div>)?',
            '',
            content,
            flags=re.DOTALL
        )

        with open(filepath, 'w', encoding='utf-8') as f:
            f.write(content)

        print(f"Processed: {filename}")
    except Exception as e:
        print(f"Error processing {filename}: {e}")

print("Floating phone button removal complete")
