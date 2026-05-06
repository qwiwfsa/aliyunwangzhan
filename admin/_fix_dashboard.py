import os, shutil

BASE = r'D:\yingyong\xampp\htdocs\hongdu\admin'

path = os.path.join(BASE, 'dashboard.html')
bak = path + '.bak'
if not os.path.exists(bak):
    shutil.copy2(path, bak)
    print('Backup saved:', bak)

with open(path, 'r', encoding='utf-8') as f:
    c = f.read()

# 1. getPagesList + savePagesList
old1 = """        // 获取当前页面列表（从localStorage获取或首次自动初始化默认值）
        function getPagesList() {
            const stored = localStorage.getItem('cms_page_list');
            if (stored) {
                return JSON.parse(stored);
            } else {
                // 默认值
                const defaults = [
                    { id: 'home', name: '首页', icon: 'fa-home', url: 'index.html', type: 'system' },
                    { id: 'about', name: '关于我们', icon: 'fa-info-circle', url: 'about.html', type: 'system' },
                    { id: 'services', name: '服务项目', icon: 'fa-briefcase', url: 'services.html', type: 'system' },
                    { id: 'cases', name: '成功案例', icon: 'fa-trophy', url: 'cases.html', type: 'system' },
                    { id: 'advantages', name: '公司优势', icon: 'fa-star', url: 'advantages.html', type: 'system' },
                    { id: 'news', name: '行业资讯', icon: 'fa-newspaper', url: 'news.html', type: 'system' },
                    { id: 'contact', name: '联系我们', icon: 'fa-envelope', url: 'contact.html', type: 'system' }
                ];
                localStorage.setItem('cms_page_list', JSON.stringify(defaults));
                return defaults;
            }
        }

        // 保存页面列表
        function savePagesList(pagesList) {
            localStorage.setItem('cms_page_list', JSON.stringify(pagesList));
        }"""
new1 = """        // 获取当前页面列表（从API获取，不再使用localStorage）
        async function getPagesList() {
            try {
                const resp = await fetch('api/list-pages.php');
                const result = await resp.json();
                if (result.success && result.data && result.data.pages) {
                    return result.data.pages.map(p => ({
                        id: p.pageId,
                        name: p.title || p.pageName,
                        icon: 'fa-file',
                        url: p.pageName + '.html',
                        type: 'custom'
                    }));
                }
            } catch(e) {
                console.error('获取页面列表失败:', e);
            }
            // API失败时返回默认列表
            return [
                { id: 'home', name: '首页', icon: 'fa-home', url: 'index.html', type: 'system' },
                { id: 'about', name: '关于我们', icon: 'fa-info-circle', url: 'about.html', type: 'system' },
                { id: 'services', name: '服务项目', icon: 'fa-briefcase', url: 'services.html', type: 'system' },
                { id: 'cases', name: '成功案例', icon: 'fa-trophy', url: 'cases.html', type: 'system' },
                { id: 'advantages', name: '公司优势', icon: 'fa-star', url: 'advantages.html', type: 'system' },
                { id: 'news', name: '行业资讯', icon: 'fa-newspaper', url: 'news.html', type: 'system' },
                { id: 'contact', name: '联系我们', icon: 'fa-envelope', url: 'contact.html', type: 'system' }
            ];
        }

        // 保存页面列表
        function savePagesList(pagesList) {
            // 页面列表由API管理，无需额外保存
        }"""
count1 = c.count(old1)
print(f'dashboard.html getPagesList: found {count1}')
if count1: c = c.replace(old1, new1)

# 2. renderPagesTable - replace pagesIndex from localStorage
old2 = """        // 渲染页面列表表格
        function renderPagesTable() {
            const tableBody = document.getElementById('pagesTableBody');
            if (!tableBody) return;
            
            const pagesIndex = JSON.parse(localStorage.getItem('cms_pages_index') || '[]');"""
new2 = """        // 渲染页面列表表格
        function renderPagesTable() {
            const tableBody = document.getElementById('pagesTableBody');
            if (!tableBody) return;
            
            // 从API获取页面列表
            let pagesIndex = [];"""
count2 = c.count(old2)
print(f'dashboard.html renderPagesTable: found {count2}')
if count2: c = c.replace(old2, new2)

# 3. renderPagesTable - remove lastUpdate from localStorage
old3 = """            const lastUpdate = localStorage.getItem('cms_last_update');
            if (lastUpdate) {
                const date = new Date(lastUpdate);
                const lastUser = localStorage.getItem('cms_username') || '-';
            } else {

            }
            const lastUser = localStorage.getItem('cms_username') || '-';"""
new3 = """            const lastUpdate = null;
            const lastUser = localStorage.getItem('cms_username') || '-';"""
count3 = c.count(old3)
print(f'dashboard.html lastUpdate: found {count3}')
if count3: c = c.replace(old3, new3)

# 4. Replace pagesIndex in initDashboard (async)
old4 = """                pagesIndex = JSON.parse(localStorage.getItem('cms_pages_index') || '[]');"""
new4 = """                // 从API获取
                try {
                    const resp = await fetch('api/list-pages.php');
                    const result = await resp.json();
                    if (result.success && result.data && result.data.pages) {
                        pagesIndex = result.data.pages.map(p => p.pageId);
                    }
                } catch(e) { pagesIndex = []; }"""
count4 = c.count(old4)
print(f'dashboard.html pagesIndex init: found {count4}')
if count4: c = c.replace(old4, new4)

# 5. activities from localStorage
old5 = """                activities = JSON.parse(localStorage.getItem('cms_activities') || '[]');"""
new5 = """                activities = []; // 活动记录通过API管理"""
count5 = c.count(old5)
print(f'dashboard.html activities: found {count5}')
if count5: c = c.replace(old5, new5)

# 6. localStorage.removeItem for cms_data
old6 = """            // 删除localStorage中的页面数据"""
new6 = """            // 数据库中数据由API删除"""
count6 = c.count(old6)
print(f'dashboard.html removeItem comment: found {count6}')
if count6: c = c.replace(old6, new6)

old6b = """            localStorage.removeItem(`cms_data_${pageId}`);"""
new6b = """            // localStorage操作已移除"""
count6b = c.count(old6b)
print(f'dashboard.html removeItem: found {count6b}')
if count6b: c = c.replace(old6b, new6b)

# 7. sets
old7 = """            localStorage.setItem('cms_pages_index', JSON.stringify(updatedIndex));"""
new7 = """            // 页面索引由list-pages.php管理"""
count7 = c.count(old7)
print(f'dashboard.html setItem pages_index: found {count7}')
if count7: c = c.replace(old7, new7)

old7b = """            localStorage.setItem('cms_page_list', JSON.stringify(defaults));"""
new7b = """            // 页面列表由API管理"""
count7b = c.count(old7b)
print(f'dashboard.html setItem page_list(defaults): found {count7b}')
if count7b: c = c.replace(old7b, new7b)

old7c = """            localStorage.setItem('cms_page_list', JSON.stringify(pagesList));"""
new7c = """            // 页面列表由API管理"""
count7c = c.count(old7c)
print(f'dashboard.html setItem page_list: found {count7c}')
if count7c: c = c.replace(old7c, new7c)

# 8. activities setItem
old8 = """            localStorage.setItem('cms_activities', JSON.stringify(activities.slice(0, 20)));"""
new8 = """            // 活动记录通过API管理"""
count8 = c.count(old8)
print(f'dashboard.html setItem activities: found {count8}')
if count8: c = c.replace(old8, new8)

# 9. Comment change
old9 = """            // 从localStorage动态构建页面列表"""
new9 = """            // 从API动态构建页面列表"""
count9 = c.count(old9)
print(f'dashboard.html comment: found {count9}')
if count9: c = c.replace(old9, new9)

# 10. Comment for activities loading
old10 = """                // 从localStorage加载活动记录"""
new10 = """                // 活动记录通过API管理"""
count10 = c.count(old10)
print(f'dashboard.html activity comment: found {count10}')
if count10: c = c.replace(old10, new10)

with open(path, 'w', encoding='utf-8') as f:
    f.write(c)
print('  dashboard.html updated')
