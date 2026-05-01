from PIL import Image
import os

# Check workspace logos
d = r'D:\OpenClaw\Data\.openclaw\workspace\zongjingli-laicai'
print("=== Workspace logos ===")
for f in sorted(os.listdir(d)):
    if f.endswith('.png'):
        try:
            img = Image.open(os.path.join(d, f))
            print(f"{f}: {img.size}")
            img.close()
        except:
            print(f"{f}: ERROR")

# Check project images
pj = r'D:\yingyong\xampp\htdocs\hongdu\images'
print("\n=== Project images/logo.png ===")
try:
    img = Image.open(os.path.join(pj, 'logo.png'))
    print(f"logo.png: {img.size}")
    img.close()
except:
    print("logo.png not found or invalid")

# Check workspace images/logo.png
print("\n=== Workspace images/logo.png ===")
try:
    img = Image.open(r'D:\OpenClaw\Data\.openclaw\workspace\zongjingli-laicai\images\logo.png')
    print(f"Size: {img.size}")
    img.close()
except:
    print("not found or invalid")

# Check website-capital-final images
print("\n=== website-capital-final images/logo.png ===")
try:
    img = Image.open(r'D:\OpenClaw\Data\.openclaw\workspace\zongjingli-laicai\website-capital-final\images\logo.png')
    print(f"Size: {img.size}")
    img.close()
except:
    print("not found or invalid")
