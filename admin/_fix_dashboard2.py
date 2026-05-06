import os, shutil

BASE = r'D:\yingyong\xampp\htdocs\hongdu\admin'

path = os.path.join(BASE, 'dashboard.html')
# Already backed up by previous run, read current state
with open(path, 'r', encoding='utf-8') as f:
    c = f.read()

changes = 0

# 1. getPages: replace the "写入默认列表" comment (already done), just fix the getPages function
old1a = """        // 获取当前页面列表（从localStorage读取，首次自动初始化默认值）"""
new1a = """        // 获取当前页面列表（优先从API获取，回退到默认值）"""
if old1a in c:
    c = c.replace(old1a, new1a)
    print('getPages comment updated')
    changes += 1

# The getPages function already has "页面列表由API管理" comment for save, good.

# 2. exportAllData - remove localStorage usage
old_exp = """        // 导出所有数据
        function exportAllData() {
            const data = {
                exportTime: new Date().toISOString(),
                pages: {}
            };

            const pagesIndex = JSON.parse(localStorage.getItem('cms_pages_index') || '[]');
            pagesIndex.forEach(page => {
                const key = `cms_content_${page.replace('.html', '')}`;
                const content = localStorage.getItem(key);
                if (content) {
                    data.pages[page] = JSON.parse(content);
                }
            });

            const blob = new Blob([JSON.stringify(data, null, 2)], { type: 'application/json' });
            const url = URL.createObjectURL(blob);
            const a = document.createElement('a');
            a.href = url;
            a.download = `cms-backup-${new Date().toISOString().split('T')[0]}.json`;
            document.body.appendChild(a);
            a.click();
            document.body.removeChild(a);
            URL.revokeObjectURL(url);

            alert('数据已导出！');
        }"""
new_exp = """        // 导出所有数据（通过API导出）
        async function exportAllData() {
            try {
                const resp = await fetch('api/list-pages.php');
                const result = await resp.json();
                const data = {
                    exportTime: new Date().toISOString(),
                    pages: {}
                };
                
                if (result.success && result.data && result.data.pages) {
                    result.data.pages.forEach(p => {
                        if (p.data) {
                            data.pages[p.pageName + '.html'] = p.data;
                        }
                    });
                }

                const blob = new Blob([JSON.stringify(data, null, 2)], { type: 'application/json' });
                const url = URL.createObjectURL(blob);
                const a = document.createElement('a');
                a.href = url;
                a.download = `cms-backup-${new Date().toISOString().split('T')[0]}.json`;
                document.body.appendChild(a);
                a.click();
                document.body.removeChild(a);
                URL.revokeObjectURL(url);

                showToast('数据已导出', 'success');
            } catch(e) {
                showToast('导出失败', 'error');
            }
        }"""
if old_exp in c:
    c = c.replace(old_exp, new_exp)
    print('exportAllData updated')
    changes += 1

# 3. dashboardDeletePage - remaining localStorage reads for cms_pages_index and cms_activities
old_del = """            const pagesIndex = JSON.parse(localStorage.getItem('cms_pages_index') || '[]');
            const updatedIndex = pagesIndex.filter(file => file !== `${pageId}.html` && file !== pageId);
            // 页面索引由list-pages.php管理
            
            // 添加活动记录
            const activities = JSON.parse(localStorage.getItem('cms_activities') || '[]');
            activities.unshift({
                type: 'delete',
                description: `删除了页面: ${pageName}`,
                time: new Date().toISOString(),
            });
            // 活动记录通过API管理"""
new_del = """            // 页面索引由list-pages.php管理
            
            // 添加活动记录（通过API记录）
            fetch('api/nav-save.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({
                    action: 'save_activity',
                    type: 'activity',
                    activity: {
                        type: 'delete',
                        description: `删除了页面: ${pageName}`,
                        time: new Date().toISOString()
                    }
                })
            }).catch(err => console.error('记录活动失败:', err));
            // 活动记录通过API管理"""
if old_del in c:
    c = c.replace(old_del, new_del)
    print('dashboardDeletePage updated')
    changes += 1

# 4. Remove the "从localStorage动态加载页面列表" comment and adjacent
old_comment = """            // 从localStorage动态加载页面列表"""
new_comment = """            // 从API动态加载页面列表"""
if old_comment in c:
    c = c.replace(old_comment, new_comment)
    print('comment updated')
    changes += 1

with open(path, 'w', encoding='utf-8') as f:
    f.write(c)
print(f'  dashboard.html updated ({changes} changes)')
