import os
import re

# 需要处理的目录
directories = [
    '.',
    'mobile',
    'tablet'
]

# HTML文件列表
html_files = [
    'index.html',
    'services.html',
    'advantages.html',
    'cases.html',
    'contact.html',
    'faq.html',
    'news.html',
    'news-detail.html'
]

# 要移除的localStorage缓存代码模式
pattern = r'<!-- 动态内容加载脚本 -->\s*<script>\s*// 从localStorage加载编辑后的内容\s*\(function\(\) \{[\s\S]*?const savedContent = localStorage\.getItem\(\'page_\' \+ pageName\);[\s\S]*?\}\)\(\);\s*</script>'

count = 0
for directory in directories:
    for filename in html_files:
        filepath = os.path.join(directory, filename)

        if not os.path.exists(filepath):
            continue

        try:
            with open(filepath, 'r', encoding='utf-8') as f:
                content = f.read()

            # 移除localStorage缓存代码
            new_content = re.sub(pattern, '', content, flags=re.MULTILINE)

            if new_content != content:
                with open(filepath, 'w', encoding='utf-8') as f:
                    f.write(new_content)
                print(f"Removed localStorage cache from: {filepath}")
                count += 1

        except Exception as e:
            print(f"Error processing {filepath}: {e}")

print(f"\nTotal files processed: {count}")
