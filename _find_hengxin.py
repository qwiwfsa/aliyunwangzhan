import os
root = 'D:\\yingyong\\xampp\\htdocs\\hongdu'
target = u'\u6052\u4fe1\u8d44\u672c'  # 恒信资本
exclude = ['backup_','node_modules','hognduziben','宝塔部署','wp-content','wp-admin','wp-includes']
found = []
for dirpath, dirnames, filenames in os.walk(root):
    skip = False
    for ex in exclude:
        if ex in dirpath:
            skip = True
            break
    if skip:
        continue
    for f in filenames:
        if not any(f.endswith(e) for e in ['.html','.php','.js','.css','.json']):
            continue
        fpath = os.path.join(dirpath, f)
        try:
            with open(fpath,'rb') as fh:
                data = fh.read()
            for enc in ['utf-8','gbk','gb18030']:
                try:
                    s = data.decode(enc)
                    if target in s:
                        found.append((fpath, enc))
                        break
                except:
                    pass
        except:
            pass
if found:
    for fp, enc in found:
        print(fp + ' [' + enc + ']')
else:
    print('NONE_FOUND')
