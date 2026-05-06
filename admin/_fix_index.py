import os, shutil

BASE = r'D:\yingyong\xampp\htdocs\hongdu\admin'

path = os.path.join(BASE, 'index.html')
bak = path + '.bak'
if not os.path.exists(bak):
    shutil.copy2(path, bak)
    print('Backup saved:', bak)

with open(path, 'r', encoding='utf-8') as f:
    c = f.read()

# 1. loadCustomPages + saveCustomPages
old1 = """        // 加载自定义页面
        function loadCustomPages() {
            const saved = localStorage.getItem('cms_custom_pages');
            if (saved) {
                customPages = JSON.parse(saved);
            }
        }

        // 保存自定义页面
        function saveCustomPages() {
            localStorage.setItem('cms_custom_pages', JSON.stringify(customPages));
        }"""
new1 = """        // 加载自定义页面（从API加载）
        function loadCustomPages() {
            // 从API获取页面列表
            fetch('api/list-pages.php')
                .then(r => r.json())
                .then(result => {
                    if (result.success && result.data && result.data.pages) {
                        customPages = result.data.pages.map(p => ({
                            id: p.pageId,
                            name: p.title || p.pageName,
                            file: p.pageName + '.html',
                            icon: 'fa-file',
                            desc: p.subtitle || '',
                            protected: false
                        }));
                    }
                })
                .catch(err => console.error('加载页面列表失败:', err));
        }

        // 保存自定义页面（通过API保存）
        function saveCustomPages() {
            // 自定义页面信息通过 create-page.php 和 delete-page.php 管理
        }"""
count1 = c.count(old1)
print(f'index.html loadCustomPages: found {count1}')
if count1: c = c.replace(old1, new1)

# 2. loadStats
old2 = """        // 加载统计数据
        function loadStats() {
            const allPages = [...defaultPages, ...customPages];
            document.getElementById('totalPages').textContent = allPages.length;

            // 统计已编辑的页面
            const pagesIndex = JSON.parse(localStorage.getItem('cms_pages_index') || '[]');
            document.getElementById('editedContent').textContent = pagesIndex.length;

            // 媒体文件数
            const images = JSON.parse(localStorage.getItem('cms_image_library') || '[]');
            document.getElementById('mediaCount').textContent = images.length;

            // 最后更新时间
            const lastUpdate = localStorage.getItem('cms_last_update');
            if (lastUpdate) {
                const date = new Date(lastUpdate);
                document.getElementById('lastUpdate').textContent = date.toLocaleDateString('zh-CN');
            } else {
                document.getElementById('lastUpdate').textContent = '从未';
            }

            // 最后操作用户
            const lastUser = localStorage.getItem('cms_username') || '-';
            document.getElementById('lastUser').textContent = lastUser;
        }"""
new2 = """        // 加载统计数据（从API获取，不再使用localStorage）
        function loadStats() {
            const allPages = [...defaultPages, ...customPages];
            document.getElementById('totalPages').textContent = allPages.length;

            // 从API获取页面信息进行统计
            fetch('api/list-pages.php')
                .then(r => r.json())
                .then(result => {
                    if (result.success && result.data && result.data.pages) {
                        document.getElementById('editedContent').textContent = result.data.total || result.data.pages.length;
                        if (result.data.pages.length > 0) {
                            const sorted = [...result.data.pages].sort((a, b) => new Date(b.lastModified) - new Date(a.lastModified));
                            if (sorted[0].lastModified) {
                                const date = new Date(sorted[0].lastModified);
                                document.getElementById('lastUpdate').textContent = date.toLocaleDateString('zh-CN');
                            }
                        }
                    }
                })
                .catch(err => {
                    document.getElementById('editedContent').textContent = '--';
                });

            // 媒体文件数（暂设为0，等待图片库API）
            document.getElementById('mediaCount').textContent = '0';

            // 最后操作用户
            const lastUser = localStorage.getItem('cms_username') || '-';
            document.getElementById('lastUser').textContent = lastUser;
        }"""
count2 = c.count(old2)
print(f'index.html loadStats: found {count2}')
if count2: c = c.replace(old2, new2)

# 3. renderPageList - remove pagesIndex from localStorage
old3 = """            const pagesIndex = JSON.parse(localStorage.getItem('cms_pages_index') || '[]');
            const allPages = [...defaultPages, ...customPages];"""
new3 = """            const allPages = [...defaultPages, ...customPages];"""
count3 = c.count(old3)
print(f'index.html renderPageList pagesIndex: found {count3}')
if count3: c = c.replace(old3, new3)

# 4. createPage - localStorage.setItem for cms_data
old4 = """            // 初始化页面数据
            const pageData = {
                pageTitle: pageTitle || pageName,
                pageSubtitle: pageDesc || '',
                lastModified: new Date().toISOString(),
                createdAt: new Date().toISOString()
            };
            localStorage.setItem(`cms_data_${pageId}`, JSON.stringify(pageData));"""
new4 = """            // 通过API初始化页面数据
            fetch('api/save.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({
                    page: pageId,
                    pageName: pageId,
                    title: pageName,
                    subtitle: pageDesc || '',
                    data: { pageTitle: pageTitle || pageName, pageSubtitle: pageDesc || '' },
                    published: false
                })
            }).catch(err => console.error('初始化页面数据失败:', err));"""
count4 = c.count(old4)
print(f'index.html createPage: found {count4}')
if count4: c = c.replace(old4, new4)

# 5. deletePage - localStorage.removeItem for cms_data
old5 = """            // 删除本地存储的相关数据
            localStorage.removeItem(`cms_data_${pageId}`);"""
new5 = """            // 数据库中数据已由delete-page.php删除"""
count5 = c.count(old5)
print(f'index.html deletePage removeItem: found {count5}')
if count5: c = c.replace(old5, new5)

# 6. addActivity
old6 = """        // 添加活动记录
        function addActivity(type, description) {
            const activities = JSON.parse(localStorage.getItem('cms_activities') || '[]');
            activities.unshift({
                type: type,
                description: description,
                time: new Date().toISOString(),
                user: localStorage.getItem('cms_username') || 'Admin'
            });
            localStorage.setItem('cms_activities', JSON.stringify(activities.slice(0, 20)));
        }"""
new6 = """        // 添加活动记录（日志记录）
        function addActivity(type, description) {
            const user = localStorage.getItem('cms_username') || 'Admin';
            // 活动记录通过nav-save.php的活动日志API保存
            fetch('api/nav-save.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({
                    action: 'save_activity',
                    type: 'activity',
                    activity: { type: type, description: description, time: new Date().toISOString(), user: user }
                })
            }).catch(err => console.error('记录活动失败:', err));
        }"""
count6 = c.count(old6)
print(f'index.html addActivity: found {count6}')
if count6: c = c.replace(old6, new6)

with open(path, 'w', encoding='utf-8') as f:
    f.write(c)
print('  index.html updated')
