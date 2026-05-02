<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=5.0">
    <meta name="description" content="Yao资金网成功案例 - 多年来已成功服务数百家企业，累计管理出资金额超百亿。查看我们的过桥资金、摆账亮资、应收账款融资等成功案例。">
    <meta name="keywords" content="成功案例,资金服务案例,过桥资金案例,摆账案例,亮资案例,融资案例,Yao资金网">
    <title>成功案例 - Yao资金网 | 专业资金业务服务商</title>
    <link rel="preconnect" href="https://cdnjs.cloudflare.com">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/cases-enhanced.css">
    <!-- Logo动态加载 -->
    <script>
    (function(){
        var xhr=new XMLHttpRequest();
        xhr.open('GET','admin/api/fetch-logo.php?t='+Date.now(),true);
        xhr.onload=function(){
            if(xhr.status>=200&&xhr.status<400){
                try{
                    var resp=JSON.parse(xhr.responseText);
                    if(resp.code===0&&resp.data){
                        if(resp.data.header_logo){
                            var hl=document.querySelector('.logo img');
                            if(hl)hl.src=resp.data.header_logo;
                        }
                        if(resp.data.footer_logo){
                            var fl=document.querySelector('.footer-logo img');
                            if(fl)fl.src=resp.data.footer_logo;
                        }
                        if(resp.data.favicon){
                            var lk=document.querySelector('link[rel="icon"]')||document.querySelector('link[rel="shortcut icon"]');
                            if(!lk){lk=document.createElement('link');lk.rel='icon';document.head.appendChild(lk);}
                            lk.href=resp.data.favicon;
                        }
                    }
                }catch(e){}
            }
        };
        xhr.send();
    })();
    </script>
<script>
(function() {
    var pageName = window.location.pathname.split('/').pop() || 'index.html';
    var xhr = new XMLHttpRequest();
    xhr.open('GET', 'admin/api/fetch-seo.php?page=' + pageName + '&t=' + Date.now(), true);
    xhr.onload = function() {
        if (xhr.status === 200) {
            try {
                var data = JSON.parse(xhr.responseText);
                if (data && data.code === 0 && data.data) {
                    var seo = data.data;
                    if (seo.page_title) document.title = seo.page_title;
                    if (seo.meta_keywords) {
                        var kw = document.querySelector('meta[name="keywords"]');
                        if (kw) kw.content = seo.meta_keywords;
                    }
                    if (seo.meta_description) {
                        var desc = document.querySelector('meta[name="description"]');
                        if (desc) desc.content = seo.meta_description;
                    }
                }
            } catch(e) {}
        }
    };
    xhr.send();
})();
</script>
<script>
(function() {
    var pageName = window.location.pathname.split('/').pop() || 'index.html';
    var xhr = new XMLHttpRequest();
    xhr.open('GET', 'admin/api/fetch-seo.php?page=' + pageName + '&t=' + Date.now(), true);
    xhr.onload = function() {
        if (xhr.status === 200) {
            try {
                var data = JSON.parse(xhr.responseText);
                if (data && data.code === 0 && data.data) {
                    var seo = data.data;
                    if (seo.page_title) document.title = seo.page_title;
                    if (seo.meta_keywords) {
                        var kw = document.querySelector('meta[name="keywords"]');
                        if (kw) kw.content = seo.meta_keywords;
                    }
                    if (seo.meta_description) {
                        var desc = document.querySelector('meta[name="description"]');
                        if (desc) desc.content = seo.meta_description;
                    }
                }
            } catch(e) {}
        }
    };
    xhr.send();
})();
</script>
</head>
<body>
    <a href="#main-content" class="skip-link">跳转到主要内容</a>

    <!-- 导航栏 -->
    <nav class="navbar" id="navbar" role="navigation" aria-label="主导航">
        <div class="navbar-container">
<a href="index.html" class="logo" aria-label="Yao资金网首页"><img src="images/logo.png?v=20260502040820" alt="Yao资金网" style="height:48px;width:auto;"></a>
            <ul class="nav-menu" role="menubar">
                <li role="none"><a href="index.html" class="nav-link" role="menuitem">首页</a></li>
                <li role="none"><a href="services.html" class="nav-link" role="menuitem">业务范围</a></li>
                <li role="none"><a href="cases.html" class="nav-link active" role="menuitem">成功案例</a></li>
                <li role="none"><a href="advantages.html" class="nav-link" role="menuitem">服务优势</a></li>
                <li role="none"><a href="news.html" class="nav-link" role="menuitem">行业资讯</a></li>
                <li role="none"><a href="faq.html" class="nav-link" role="menuitem">常见问题</a></li>
                <li role="none"><a href="contact.html" class="nav-link" role="menuitem">联系我们</a></li>
            </ul>

            <button class="search-toggle" id="searchToggle" aria-label="打开搜索" aria-expanded="false">
                <i class="fas fa-search" aria-hidden="true"></i>
            </button>
            
            <button class="mobile-menu-btn" id="mobileMenuBtn" aria-label="打开菜单" aria-expanded="false">
                <span></span>
                <span></span>
                <span></span>
            </button>
        </div>
    </nav>

    <main id="main-content">
        <!-- 成功案例Banner图片 -->
        <div class="business-banner">
            <img src="images/business-banner.jpg" alt="成功案例">
        </div>

        <!-- 页面标题区 -->
        <section class="page-header">
            <div class="page-header-container">
                <div class="page-header-badge">
                    <i class="fas fa-trophy"></i>
                    <span>SUCCESS CASES</span>
                </div>
                <h1 class="page-header-title">成功案例</h1>
                <p class="page-header-subtitle">多年来，我们已成功服务数百家企业，累计管理资金规模超百亿。每一个案例都是我们专业能力的见证。</p>
            </div>
        </section>

        <!-- 案例展示 -->
        <section class="page-content">
            <div class="section-container">
                
                <!-- 筛选区域 -->
                <div class="cases-filter-section">
                    <!-- 业务类型筛选 -->
                    <div class="filter-group">
                        <span class="filter-label">业务类型</span>
                        <div class="filter-buttons" id="typeFilter">
                            <button class="filter-btn active" data-filter="all" data-type="type">全部</button>
                            <!-- 业务类型按钮将通过JS动态生成 -->
                        </div>
                    </div>
                    

                </div>

                <!-- 案例卡片网格 -->
                <div class="cases-grid-enhanced" id="casesGrid">
                    <!-- 案例卡片将通过JS动态生成 -->
                </div>

                <!-- 分页 -->
                <div class="cases-pagination" id="paginationContainer" style="text-align: center; margin-top: 40px; padding: 20px;">
                    <!-- 分页按钮由JS生成 -->
                </div>

                <!-- 案例统计 -->
                <div class="cases-stats-section">
                    <div class="cases-stats-grid">
                        <div class="cases-stat-box">
                            <div class="cases-stat-icon">
                                <i class="fas fa-handshake"></i>
                            </div>
                            <div class="cases-stat-number">500+</div>
                            <div class="cases-stat-label">服务企业</div>
                        </div>
                        <div class="cases-stat-box">
                            <div class="cases-stat-icon">
                                <i class="fas fa-coins"></i>
                            </div>
                            <div class="cases-stat-number">100亿+</div>
                            <div class="cases-stat-label">累计管理规模</div>
                        </div>
                        <div class="cases-stat-box">
                            <div class="cases-stat-icon">
                                <i class="fas fa-check-circle"></i>
                            </div>
                            <div class="cases-stat-number">99%</div>
                            <div class="cases-stat-label">业务成功率</div>
                        </div>
                        <div class="cases-stat-box">
                            <div class="cases-stat-icon">
                                <i class="fas fa-star"></i>
                            </div>
                            <div class="cases-stat-number">98%</div>
                            <div class="cases-stat-label">客户满意度</div>
                        </div>
                    </div>
                </div>

            </div>
        </section>

        <!-- CTA区域 -->
        <section class="cta-section-cases" aria-labelledby="cta-title">
            <div class="cta-container">
                <h2 class="cta-title" id="cta-title">成为我们的下一个成功案例</h2>
                <p class="cta-subtitle">无论您面临何种资金需求，我们都能为您提供专业解决方案</p>
                <div class="cta-wrapper cta-center">
                    <button type="button" class="btn btn-white" style="cursor: default;" onclick="return false;">
                        <i class="fas fa-phone-alt"></i>
                        立即咨询
                    </button>
                    <span class="cta-contact-info-light">135-5288-3008</span>
                </div>
            </div>
        </section>
    </main>

    <!-- 右侧边浮动电话按钮 -->
    <div class="chat-widget" id="chatWidget" aria-label="联系电话">
        <button class="chat-widget-btn" id="chatWidgetBtn" aria-label="拨打电话" aria-expanded="false">
            <i class="fas fa-phone-alt" aria-hidden="true"></i>
        </button>
    </div>

    <!-- 页脚 -->
<?php include 'includes/footer.php'; ?>


    <script src="js/main.js"></script>
    <script>
        // 案例数据 - 将从CMS数据源动态加载
        let casesData = [];

        // 当前筛选状态
        let currentFilters = {
            type: 'all'
        };

        // 获取类型标签样式类名
        function getTypeClass(type) {
            const typeMap = {
                '过桥': 'bridge',
                '摆账': 'display',
                '亮资': 'proof',
                '冲量': 'deposit',
                '定增': 'placement',
                '应收账款': 'receivable'
            };
            return typeMap[type] || 'bridge';
        }

        // 获取业务类型图标
        function getTypeIcon(type) {
            const iconMap = {
                '过桥': 'fa-exchange-alt',
                '摆账': 'fa-hand-holding-usd',
                '亮资': 'fa-eye',
                '冲量': 'fa-chart-line',
                '定增': 'fa-plus-circle',
                '应收账款': 'fa-file-invoice-dollar'
            };
            return iconMap[type] || 'fa-briefcase';
        }

        // 打开图片预览
        function openLightbox(imageSrc) {
            const lightbox = document.getElementById('imageLightbox');
            const lightboxImage = document.getElementById('lightboxImage');
            lightboxImage.src = imageSrc;
            lightbox.style.display = 'flex';
            document.body.style.overflow = 'hidden'; // 禁止背景滚动
        }
        
        // 关闭图片预览
        function closeLightbox() {
            const lightbox = document.getElementById('imageLightbox');
            lightbox.style.display = 'none';
            document.body.style.overflow = ''; // 恢复背景滚动
        }
        
        // 点击背景关闭
        document.addEventListener('click', function(e) {
            const lightbox = document.getElementById('imageLightbox');
            if (e.target === lightbox) {
                closeLightbox();
            }
        });
        
        // ESC键关闭
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeLightbox();
            }
        });

        // 设置筛选事件
        function setupFilters() {
            const typeButtons = document.querySelectorAll('#typeFilter .filter-btn');

            typeButtons.forEach(btn => {
                btn.addEventListener('click', function() {
                    typeButtons.forEach(b => b.classList.remove('active'));
                    this.classList.add('active');
                    currentFilters.type = this.dataset.filter;
                    filterCases();
                });
            });
        }

        // 分页变量
        let currentPage = 1;
        const itemsPerPage = 8;
        let filteredCases = [...casesData];

        // 渲染分页
        function renderPagination() {
            const totalPages = Math.ceil(filteredCases.length / itemsPerPage);
            const container = document.getElementById('paginationContainer');
            if (!container) return;
            
            if (totalPages <= 1) {
                container.innerHTML = '';
                return;
            }
            
            container.innerHTML = `
                <div style="display: inline-flex; align-items: center; gap: 15px;">
                    <button onclick="changePage(${currentPage - 1})" ${currentPage <= 1 ? 'disabled' : ''} 
                        style="padding: 10px 20px; background: ${currentPage <= 1 ? '#ccc' : '#1e3a8a'}; color: white; border: none; border-radius: 4px; cursor: ${currentPage <= 1 ? 'not-allowed' : 'pointer'}; font-size: 14px;">上一页</button>
                    <span style="font-size: 16px; color: #333;">第 ${currentPage} 页 / 共 ${totalPages} 页</span>
                    <button onclick="changePage(${currentPage + 1})" ${currentPage >= totalPages ? 'disabled' : ''} 
                        style="padding: 10px 20px; background: ${currentPage >= totalPages ? '#ccc' : '#1e3a8a'}; color: white; border: none; border-radius: 4px; cursor: ${currentPage >= totalPages ? 'not-allowed' : 'pointer'}; font-size: 14px;">下一页</button>
                </div>
            `;
        }

        // 切换页面
        function changePage(newPage) {
            const totalPages = Math.ceil(filteredCases.length / itemsPerPage);
            if (newPage < 1 || newPage > totalPages) return;
            currentPage = newPage;
            renderCasesPage();
            renderPagination();
            window.scrollTo({ top: 0, behavior: 'smooth' });
        }

        // 渲染当前页案例
        function renderCasesPage() {
            const grid = document.getElementById('casesGrid');
            const start = (currentPage - 1) * itemsPerPage;
            const end = start + itemsPerPage;
            const pageCases = filteredCases.slice(start, end);
            
            // 获取基础路径（如 /hongdu/）
            const basePath = window.location.pathname.substring(0, window.location.pathname.lastIndexOf('/') + 1);
            
            // 辅助函数：将相对路径解析为正确URL
            function resolveImgUrl(path) {
                if (!path) return '';
                if (path.startsWith('http')) return path;
                // 存储的路径是相对路径如 uploads/xxx.jpg，需要用 basePath 拼，不能用 /
                return path.startsWith('/') ? path : basePath + path;
            }
            
            if (pageCases.length === 0) {
                grid.innerHTML = `
                    <div class="cases-empty">
                        <i class="fas fa-folder-open"></i>
                        <p class="cases-empty-text">暂无符合条件的案例</p>
                    </div>
                `;
                return;
            }
            
            grid.innerHTML = pageCases.map(caseItem => {
                const typeClass = getTypeClass(caseItem.type);
                // 优先使用 coverImage 字段，其次使用 images 数组的第一张，最后使用 image 字段或默认图片
                let imagePath = resolveImgUrl(caseItem.coverImage);
                if (!imagePath && caseItem.images && caseItem.images.length > 0) {
                    imagePath = resolveImgUrl(caseItem.images[0]);
                }
                if (!imagePath) {
                    imagePath = resolveImgUrl(caseItem.image);
                }
                if (!imagePath) {
                    imagePath = basePath + 'images/cases/default.jpg';
                }
                // 获取案例详情内容 - 优先使用 summary 字段，其次使用 detail 字段
                const caseDetail = caseItem.summary || caseItem.detail || '暂无详情描述';
                return `
                    <article class="case-card-enhanced" data-id="${caseItem.id}">
                        <div class="case-card-image" onclick="openLightbox('${imagePath}')">
                            <img src="${imagePath}" alt="${caseItem.title}" loading="lazy">
                        </div>
                        <div class="case-card-content">
                            <h3 class="case-card-title" onclick="window.location.href='case-detail.html?id=${caseItem.id}'" style="cursor: pointer;">${caseItem.title}</h3>
                            <p class="case-card-summary" onclick="window.location.href='case-detail.html?id=${caseItem.id}'" style="cursor: pointer;">${caseDetail}</p>
                            <div class="case-card-meta">
                                <div class="case-card-amount" onclick="window.location.href='case-detail.html?id=${caseItem.id}'" style="cursor: pointer;">
                                    <span class="case-card-amount-label">出资金额</span>
                                    <span class="case-card-amount-value">${caseItem.amount || '-'}</span>
                                </div>
                                <button class="btn-view-detail" onclick="window.location.href='case-detail.html?id=${caseItem.id}'">
                                    查看详情
                                </button>
                            </div>
                        </div>
                    </article>
                `;
            }).join('');
        }

        // 筛选案例
        function filterCases() {
            currentPage = 1;
            filteredCases = casesData.filter(caseItem => {
                return currentFilters.type === 'all' || caseItem.type === currentFilters.type;
            });
            renderCasesPage();
            renderPagination();
        }

        // 从localStorage加载案例数据（与后台管理保持一致）
        function loadCasesFromLocal() {
            try {
                const cases = JSON.parse(localStorage.getItem('cms_cases') || '[]');
                return cases.filter(c => c.status === 'published'); // 只显示已发布的案例
            } catch (e) {
                console.error('从localStorage加载失败:', e);
                return [];
            }
        }

        // 从服务器API加载案例数据
        async function loadCasesFromServer() {
            try {
                // 获取当前路径，确保API路径正确
                const basePath = window.location.pathname.substring(0, window.location.pathname.lastIndexOf('/') + 1);
                // 添加时间戳和随机数防止缓存
                const timestamp = Date.now();
                const random = Math.random().toString(36).substring(7);
                const apiUrl = basePath + 'api/cases.php?t=' + timestamp + '&r=' + random;
                console.log('[Cases] 正在从API加载:', apiUrl);
                
                const response = await fetch(apiUrl, {
                    method: 'GET',
                    cache: 'no-store', // 强制不使用缓存
                    headers: {
                        'Accept': 'application/json',
                        'Cache-Control': 'no-cache, no-store, must-revalidate',
                        'Pragma': 'no-cache',
                        'Expires': '0'
                    }
                });
                
                // 检查响应是否为JSON
                const contentType = response.headers.get('content-type');
                if (!contentType || !contentType.includes('application/json')) {
                    const text = await response.text();
                    console.error('[Cases] API返回非JSON格式:', text.substring(0, 200));
                    return [];
                }
                
                const result = await response.json();
                console.log('[Cases] API返回:', result);
                
                if (result.success && result.cases) {
                    // 只返回已发布的案例，并转换数据格式
                    const publishedCases = result.cases
                        .filter(c => c.status === 'published')
                        .map(c => ({
                            id: c.id,
                            title: c.title,
                            type: c.type,
                            city: c.city || '未知',
                            summary: c.summary || '',
                            amount: c.amount || '-',
                            period: c.period || '-',
                            detail: c.detail || '',
                            year: c.year || '',
                            image: c.image,
                            coverImage: c.coverImage,
                            images: c.images || [],
                            hasVideo: c.hasVideo || false,
                            video: c.video || null
                        }));
                    console.log('[Cases] 已发布案例数量:', publishedCases.length);
                    return publishedCases;
                }
            } catch (error) {
                console.error('[Cases] 服务器加载失败:', error);
            }
            return [];
        }

        // 业务类型数据
        let caseTypes = [];

        // 从服务器加载业务类型
        async function loadCaseTypesFromServer() {
            try {
                const basePath = window.location.pathname.substring(0, window.location.pathname.lastIndexOf('/') + 1);
                const timestamp = Date.now();
                const apiUrl = basePath + 'api/case/types.php?t=' + timestamp;
                console.log('[Cases] 正在加载业务类型:', apiUrl);
                
                const response = await fetch(apiUrl, {
                    method: 'GET',
                    cache: 'no-store',
                    headers: {
                        'Accept': 'application/json',
                        'Cache-Control': 'no-cache, no-store, must-revalidate'
                    }
                });
                
                if (!response.ok) {
                    throw new Error('HTTP ' + response.status);
                }
                
                // 检查响应类型
                const contentType = response.headers.get('content-type');
                if (!contentType || !contentType.includes('application/json')) {
                    const text = await response.text();
                    console.error('[Cases] 业务类型API返回非JSON格式:', text.substring(0, 200));
                    return [];
                }
                
                const result = await response.json();
                console.log('[Cases] 业务类型API返回:', result);
                
                if (result.success && result.data && Array.isArray(result.data)) {
                    console.log('[Cases] 成功加载业务类型:', result.data.length, '个');
                    return result.data;
                } else {
                    console.warn('[Cases] 业务类型API返回数据格式不正确:', result);
                }
            } catch (error) {
                console.error('[Cases] 加载业务类型失败:', error);
            }
            return [];
        }

        // 渲染业务类型筛选按钮
        function renderTypeFilters() {
            const container = document.getElementById('typeFilter');
            if (!container) {
                console.error('[Cases] typeFilter容器不存在');
                return;
            }
            
            console.log('[Cases] 渲染业务类型按钮, 数量:', caseTypes.length);
            
            // 保留"全部"按钮
            let html = '<button class="filter-btn active" data-filter="all" data-type="type">全部</button>';
            
            // 动态生成业务类型按钮
            caseTypes.forEach(type => {
                // 使用name作为显示名称和筛选值
                const displayName = type.name;
                const filterValue = type.name;
                html += `<button class="filter-btn" data-filter="${filterValue}" data-type="type">${displayName}</button>`;
            });
            
            container.innerHTML = html;
            
            // 重新绑定筛选事件
            setupFilters();
        }

        // 加载案例数据（优先从服务器，失败则显示空状态）
        async function loadCasesData() {
            console.log('[Cases] 开始加载案例数据...');
            
            // 清除可能存在的旧缓存，确保获取最新数据
            try {
                localStorage.removeItem('cases_last_update');
                localStorage.removeItem('cases_cache_time');
            } catch (e) {
                console.log('[Cases] 清理缓存标记失败:', e);
            }
            
            // 并行加载业务类型和案例数据
            const [types, cases] = await Promise.all([
                loadCaseTypesFromServer(),
                loadCasesFromServer()
            ]);
            
            console.log('[Cases] 数据加载完成 - 业务类型:', types.length, '个, 案例:', cases.length, '个');
            
            // 更新业务类型
            if (types.length > 0) {
                console.log('[Cases] 使用服务器返回的业务类型');
                caseTypes = types;
                renderTypeFilters();
                // 保存到localStorage供离线使用
                localStorage.setItem('cms_case_types', JSON.stringify(caseTypes));
            } else {
                console.log('[Cases] 服务器无业务类型，尝试从localStorage加载');
                // 从localStorage加载业务类型作为后备
                try {
                    const cachedTypes = JSON.parse(localStorage.getItem('cms_case_types') || '[]');
                    if (cachedTypes.length > 0) {
                        console.log('[Cases] 从localStorage加载业务类型:', cachedTypes.length, '个');
                        caseTypes = cachedTypes;
                        renderTypeFilters();
                    } else {
                        console.warn('[Cases] localStorage中也没有业务类型数据');
                    }
                } catch (e) {
                    console.log('[Cases] 从localStorage加载业务类型失败:', e);
                }
            }
            
            // 处理案例数据 - 只使用服务器数据，不再从localStorage合并
            let finalCases = cases;
            
            // 按ID去重 - 防止同一案例重复显示
            const uniqueCases = [];
            const seenIds = new Set();
            for (const caseItem of finalCases) {
                if (caseItem.id && !seenIds.has(caseItem.id)) {
                    seenIds.add(caseItem.id);
                    uniqueCases.push(caseItem);
                } else if (!caseItem.id) {
                    // 没有ID的案例，使用标题+类型组合作为唯一标识
                    const uniqueKey = (caseItem.title || '') + '|' + (caseItem.type || '');
                    if (!seenIds.has(uniqueKey)) {
                        seenIds.add(uniqueKey);
                        uniqueCases.push(caseItem);
                    }
                }
            }
            finalCases = uniqueCases;
            console.log('[Cases] 去重后案例数量:', finalCases.length);
            
            // 如果都没有数据，显示空状态
            if (finalCases.length === 0) {
                console.log('暂无案例数据');
            }
            
            casesData = finalCases;
            filteredCases = [...casesData];
            renderCasesPage();
            renderPagination();
        }

        // 初始化
        document.addEventListener('DOMContentLoaded', function() {
            loadCasesData();
            
            // 监听localStorage变化（用于后台修改后自动刷新）
            window.addEventListener('storage', function(e) {
                if (e.key === 'cms_case_types' || e.key === 'cms_cases' || e.key === 'cms_case_types_update') {
                    console.log('[Cases] 检测到数据变化，重新加载...');
                    loadCasesData();
                }
            });
            
            // 页面可见性变化时刷新（从后台返回时）
            document.addEventListener('visibilitychange', function() {
                if (document.visibilityState === 'visible') {
                    console.log('[Cases] 页面重新可见，检查数据更新...');
                    loadCasesData();
                }
            });
        });
        
        // 强制刷新数据（供外部调用）
        function forceRefreshCases() {
            console.log('[Cases] 强制刷新数据...');
            // 清除所有缓存
            localStorage.removeItem('cms_case_types');
            localStorage.removeItem('cases_last_update');
            localStorage.removeItem('cases_cache_time');
            // 重新加载
            loadCasesData();
        }
    </script>
    
    <!-- 图片预览弹窗 (Lightbox) -->
    <div id="imageLightbox" class="lightbox-overlay" style="display: none;">
        <div class="lightbox-container">
            <button class="lightbox-close" onclick="closeLightbox()" aria-label="关闭预览">
                <i class="fas fa-times"></i>
            </button>
            <img id="lightboxImage" src="" alt="大图预览">
        </div>
    </div>

    <!-- Lightbox 样式 -->
    <style>
        .lightbox-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.9);
            z-index: 9999;
            display: flex;
            align-items: center;
            justify-content: center;
            animation: fadeIn 0.3s ease;
        }
        
        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }
        
        .lightbox-container {
            position: relative;
            max-width: 90%;
            max-height: 90%;
        }
        
        .lightbox-container img {
            max-width: 100%;
            max-height: 90vh;
            object-fit: contain;
            border-radius: 4px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.5);
        }
        
        .lightbox-close {
            position: absolute;
            top: -50px;
            right: 0;
            width: 40px;
            height: 40px;
            background: rgba(255, 255, 255, 0.2);
            border: none;
            border-radius: 50%;
            color: white;
            font-size: 20px;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: background 0.3s ease;
        }
        
        .lightbox-close:hover {
            background: rgba(255, 255, 255, 0.4);
        }
        
        /* 点击图片的指针样式 */
        .case-card-image {
            cursor: pointer;
        }
        
        .case-card-image img {
            transition: transform 0.3s ease;
        }
        
        .case-card-image:hover img {
            transform: scale(1.05);
        }
    </style>

    <!-- CMS Editor -->
    <script>
        // 检查是否需要加载编辑器
        (function() {
            console.log('[CMS] 初始化检查...');
            
            const urlParams = new URLSearchParams(window.location.search);
            const isEditMode = urlParams.get('edit') === 'true';
            const isLoggedIn = localStorage.getItem('cms_logged_in') === 'true';
            
            console.log('[CMS] 编辑模式:', isEditMode);
            console.log('[CMS] 登录状态:', isLoggedIn);
            
            if (isEditMode && isLoggedIn) {
                console.log('[CMS] 开始加载编辑器...');
                
                // 加载编辑器样式
                const editorCss = document.createElement('link');
                editorCss.rel = 'stylesheet';
                editorCss.href = 'admin/editor.css';
                editorCss.onerror = function() {
                    console.error('[CMS] 编辑器样式加载失败');
                };
                document.head.appendChild(editorCss);
                
                // 加载编辑器脚本
                const editorScript = document.createElement('script');
                editorScript.src = 'admin/editor.js';
                editorScript.onload = function() {
                    console.log('[CMS] 编辑器脚本加载成功');
                };
                editorScript.onerror = function() {
                    console.error('[CMS] 编辑器脚本加载失败');
                };
                document.body.appendChild(editorScript);
            } else if (isEditMode && !isLoggedIn) {
                console.log('[CMS] 未登录，重定向到登录页');
                window.location.href = 'admin/login.html?redirect=' + encodeURIComponent(window.location.href);
            }
        })();
    </script>
</body>
</html>

