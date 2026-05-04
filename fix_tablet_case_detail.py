import re

filepath = 'tablet/case-detail.html'

with open(filepath, 'r', encoding='utf-8') as f:
    content = f.read()

# Fix image paths
content = re.sub(r'src="images/', r'src="../images/', content)
content = re.sub(r'href="images/', r'href="../images/', content)

# Fix js paths
content = re.sub(r'src="js/', r'src="../js/', content)

# Fix admin paths
content = re.sub(r'src="admin/', r'src="../admin/', content)
content = re.sub(r'href="admin/', r'href="../admin/', content)

# Fix uploads paths
content = re.sub(r'src="uploads/', r'src="../uploads/', content)

print("Fixed paths in tablet/case-detail.html")

with open(filepath, 'w', encoding='utf-8') as f:
    f.write(content)
