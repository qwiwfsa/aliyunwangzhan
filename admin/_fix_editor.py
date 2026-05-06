import os, shutil

BASE = r'D:\yingyong\xampp\htdocs\hongdu\admin'

path = os.path.join(BASE, 'editor.html')
bak = path + '.bak'
if not os.path.exists(bak):
    shutil.copy2(path, bak)
    print('Backup saved:', bak)

with open(path, 'r', encoding='utf-8') as f:
    c = f.read()

# Replace loadData
old1 = """        // 加载数据
        function loadData() {
            const saved = localStorage.getItem(`cms_data_${pageId}`);
            if (saved) {
                currentData = JSON.parse(saved);
            } else {
                currentData = { ...defaultData[pageId] };
            }
        }"""
new1 = """        // 加载数据（从API加载，不再使用localStorage）
        function loadData() {
            // 尝试从API获取页面数据
            fetch('api/list-pages.php')
                .then(r => r.json())
                .then(result => {
                    if (result.success && result.data && result.data.pages) {
                        const pageInfo = result.data.pages.find(p => p.pageId === pageId);
                        if (pageInfo) {
                            if (pageInfo.data) {
                                currentData = { ...defaultData[pageId], ...pageInfo.data };
                            } else {
                                currentData = { ...defaultData[pageId] };
                            }
                        } else {
                            currentData = { ...defaultData[pageId] };
                        }
                    } else {
                        currentData = { ...defaultData[pageId] };
                    }
                })
                .catch(() => {
                    currentData = { ...defaultData[pageId] };
                });
        }"""
count = c.count(old1)
print(f'editor.html loadData: found {count} occurrence(s)')
if count > 0:
    c = c.replace(old1, new1)

# Replace saveData
old2 = """            localStorage.setItem(`cms_data_${pageId}`, JSON.stringify(data));
            currentData = data;
            markSaved();
            showToast('保存成功', 'success');"""
new2 = """            const config = pageConfigs[pageId];
            const payload = {
                page: pageId,
                pageName: pageId,
                title: config ? config.name : pageId,
                subtitle: '',
                data: data,
                published: false
            };
            fetch('api/save.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(payload)
            })
            .then(r => r.json())
            .then(result => {
                if (result.success) {
                    currentData = data;
                    markSaved();
                    showToast('保存成功', 'success');
                } else {
                    showToast('保存失败: ' + (result.message || '未知错误'), 'error');
                }
            })
            .catch(err => {
                showToast('保存失败，无法连接服务器', 'error');
            });"""
count = c.count(old2)
print(f'editor.html saveData: found {count} occurrence(s)')
if count > 0:
    c = c.replace(old2, new2)

# Replace publishPage
old3 = """            saveData();
            
            // 模拟发布过程
            const data = collectData();
            data.published = true;
            data.publishedAt = new Date().toISOString();
            localStorage.setItem(`cms_data_${pageId}`, JSON.stringify(data));

            showToast('页面发布成功！', 'success');"""
new3 = """            const data = collectData();
            data.published = true;
            data.publishedAt = new Date().toISOString();

            const config = pageConfigs[pageId];
            const payload = {
                page: pageId,
                pageName: pageId,
                title: config ? config.name : pageId,
                subtitle: '',
                data: data,
                published: true
            };
            fetch('api/publish.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(payload)
            })
            .then(r => r.json())
            .then(result => {
                if (result.success) {
                    currentData = data;
                    markSaved();
                    showToast('页面发布成功！', 'success');
                    refreshPreview();
                } else {
                    showToast('发布失败: ' + (result.message || '未知错误'), 'error');
                }
            })
            .catch(err => {
                showToast('发布失败，无法连接服务器', 'error');
            });"""
count = c.count(old3)
print(f'editor.html publishPage: found {count} occurrence(s)')
if count > 0:
    c = c.replace(old3, new3)

with open(path, 'w', encoding='utf-8') as f:
    f.write(c)
print('  editor.html updated')
