<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=5.0">
    <meta name="description" content="Yao资金网行业资讯 - 亮资知识、摆账流程、资金行业政策、企业融资常识。了解最新行业动态与业务资讯">
    <meta name="keywords" content="亮资知识,摆账流程,资金行业政策,企业融资常识,过桥资金,摆账业务,银行存款,应收账款融资">
    <title>行业资讯 - Yao资金网</title>
    <link rel="preconnect" href="https://cdnjs.cloudflare.com">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/page-custom.css">
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
                <li role="none"><a href="cases.html" class="nav-link" role="menuitem">成功案例</a></li>
                <li role="none"><a href="advantages.html" class="nav-link" role="menuitem">服务优势</a></li>
                <li role="none"><a href="news.html" class="nav-link active" role="menuitem">行业资讯</a></li>
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
        <!-- 页面标题区 -->
        <section class="page-header">
            <div class="page-header-container">
                <div class="page-header-badge">
                    <i class="fas fa-newspaper"></i>
                    <span>NEWS & INSIGHTS</span>
                </div>
                <h1 class="page-header-title">行业资讯</h1>
                <p class="page-header-subtitle">了解最新行业动态与业务资讯</p>
            </div>
        </section>

        <!-- 资讯内容 - 可编辑区域 -->
        <section class="page-content">
            <div class="section-container">
                
                <!-- 资讯分类 -->
                <div class="editable-section" data-section="news-categories">
                    <div class="news-categories" id="newsCategories">
                        <a href="#" class="news-category active" data-cat-id="0">全部资讯</a>
                        <!-- 分类将通过JS动态加载 -->
                    </div>
                </div>

                <!-- 精选资讯（大图展示） -->
                <div class="editable-section" data-section="news-featured">
                    <div class="news-featured-grid">
                        <!-- 精选资讯卡片已删除 -->
                    </div>
                </div>

                <!-- 资讯列表 - 卡片式设计 -->
                <div class="editable-section" data-section="news-list">
                    <div class="news-list-container">
                        <!-- 资讯文章 1 -->
                        <article class="news-card">
                            <div class="news-thumb">
                                <img src="images/news/news-1.jpg" alt="亮资业务">
                            </div>
                            <div class="news-content">
                                <h3><a href="news-detail-1.html">亮资业务助力企业展示资金实力</a></h3>
                                <p class="news-excerpt">亮资业务是企业在投标、验资、审计等场景下展示资金实力的重要方式。本文详细介绍亮资业务的应用场景与操作要点...</p>
                                <div class="news-footer">
                                    <a href="news-detail-1.html" class="news-more">查看更多 →</a>
                                    <time class="news-date">2024-04-05</time>
                                </div>
                            </div>
                        </article>

                        <!-- 资讯文章 2 -->
                        <article class="news-card">
                            <div class="news-thumb">
                                <img src="images/news/news-2.jpg" alt="过桥资金">
                            </div>
                            <div class="news-content">
                                <h3><a href="news-detail-2.html">过桥资金：企业短期融资的最佳选择</a></h3>
                                <p class="news-excerpt">过桥资金是解决企业短期资金周转问题的有效工具。本文分析过桥资金的特点、适用场景及申请流程...</p>
                                <div class="news-footer">
                                    <a href="news-detail-2.html" class="news-more">查看更多 →</a>
                                    <time class="news-date">2024-03-28</time>
                                </div>
                            </div>
                        </article>

                        <!-- 资讯文章 3 -->
                        <article class="news-card">
                            <div class="news-thumb">
                                <img src="images/news/news-3.jpg" alt="应收账款融资">
                            </div>
                            <div class="news-content">
                                <h3><a href="news-detail-3.html">应收账款融资：盘活企业存量资产</a></h3>
                                <p class="news-excerpt">应收账款融资帮助企业将闲置的应收账款转化为流动资金。本文探讨应收账款融资的操作模式与风险控制...</p>
                                <div class="news-footer">
                                    <a href="news-detail-3.html" class="news-more">查看更多 →</a>
                                    <time class="news-date">2024-03-20</time>
                                </div>
                            </div>
                        </article>

                        <!-- 资讯文章 4 -->
                        <article class="news-card">
                            <div class="news-thumb">
                                <img src="images/news/news-4.jpg" alt="银行存款业务">
                            </div>
                            <div class="news-content">
                                <h3><a href="news-detail-4.html">银行存款业务：优化企业资金配置</a></h3>
                                <p class="news-excerpt">银行存款业务是企业资金管理的基础。本文介绍如何通过合理的存款安排优化企业资金配置，提升资金使用效率...</p>
                                <div class="news-footer">
                                    <a href="news-detail-4.html" class="news-more">查看更多 →</a>
                                    <time class="news-date">2024-03-15</time>
                                </div>
                            </div>
                        </article>
                    </div>
                </div>

                <!-- 分页 -->
                <div class="editable-section" data-section="news-pagination">
                    <div class="news-pagination">
                        <a href="#" class="pagination-btn disabled"><i class="fas fa-chevron-left"></i></a>
                        <a href="#" class="pagination-btn active">1</a>
                        <a href="#" class="pagination-btn disabled"><i class="fas fa-chevron-right"></i></a>
                    </div>
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
    

    <!-- 动态加载资讯文章 -->
    <script>
        // 当前选中的分类
        let currentCategoryId = 0;
        let allArticlesCache = [];

        // 从服务器API加载文章和分类
        async function loadAllFromServer() {
            try {
                const response = await fetch('api/news.php?t=' + Date.now(), {
                    method: 'GET',
                    cache: 'no-store',
                    headers: { 'Accept': 'application/json', 'Cache-Control': 'no-cache' }
                });
                if (!response.ok) return null;
                const result = await response.json();
                if (result.success && result.data && result.data.news) {
                    allArticlesCache = result.data.news;
                    console.log('[News] 从数据库加载了', allArticlesCache.length, '篇文章');
                    if (result.data.categories && Array.isArray(result.data.categories)) {
                                            }
                    return result.data;
                }
                return null;
            } catch (error) {
                console.error('[News] API加载失败:', error);
                return null;
            }
        }

        // 加载分类
        function loadCategories(apiData) {
            const categoriesContainer = document.getElementById('newsCategories');
            if (!categoriesContainer) return;

            const allLink = categoriesContainer.querySelector('[data-cat-id="0"]');
            categoriesContainer.innerHTML = '';
            if (allLink) {
                allLink.addEventListener('click', function(e) {
                    e.preventDefault();
                    currentCategoryId = 0;
                    updateActiveCategory();
                    renderArticles(allArticlesCache);
                });
                categoriesContainer.appendChild(allLink);
            }

            const categories = (apiData && apiData.categories) 
                ? apiData.categories 
                : []
            if (categories.length > 0) {
                categories.forEach(cat => {
                    const catLink = document.createElement('a');
                    catLink.href = '#';
                    catLink.className = 'news-category';
                    catLink.textContent = cat.name;
                    catLink.dataset.catId = cat.id;
                    catLink.addEventListener('click', function(e) {
                        e.preventDefault();
                        currentCategoryId = cat.id;
                        updateActiveCategory();
                        const filtered = allArticlesCache.filter(a => {
                            const cid = a.categoryId || a.category_id;
                            return cid == cat.id;
                        });
                        renderArticles(filtered);
                    });
                    categoriesContainer.appendChild(catLink);
                });
            } else {
                const defaultCategories = ['行业动态', '政策解读', '业务知识', '公司新闻'];
                defaultCategories.forEach((name, index) => {
                    const catLink = document.createElement('a');
                    catLink.href = '#';
                    catLink.className = 'news-category';
                    catLink.textContent = name;
                    catLink.dataset.catId = index + 1;
                    catLink.addEventListener('click', function(e) {
                        e.preventDefault();
                        currentCategoryId = index + 1;
                        updateActiveCategory();
                        const filtered = allArticlesCache.filter(a => {
                            const cid = a.categoryId || a.category_id;
                            return cid == index + 1;
                        });
                        renderArticles(filtered);
                    });
                    categoriesContainer.appendChild(catLink);
                });
            }
        }

        function updateActiveCategory() {
            document.querySelectorAll('.news-category').forEach(cat => {
                cat.classList.remove('active');
                if (parseInt(cat.dataset.catId) === currentCategoryId) {
                    cat.classList.add('active');
                }
            });
        }

        function renderArticles(articles) {
            const newsContainer = document.querySelector('.news-list-container');
            if (!newsContainer) return;

            if (!articles || articles.length === 0) {
                newsContainer.innerHTML = '<div class="news-empty"><p>该分类下暂无文章</p></div>';
                return;
            }

            const sorted = [...articles].sort((a, b) => {
                const da = new Date(a.publishDate || a.created_at || 0);
                const db = new Date(b.publishDate || b.created_at || 0);
                return db - da;
            });

            newsContainer.innerHTML = '';
            sorted.forEach(article => {
                newsContainer.insertAdjacentHTML('beforeend', createNewsCard(article));
            });
            console.log('[News] 渲染完成，共', sorted.length, '篇');
        }

        function isValidImage(imageData) {
            if (!imageData || typeof imageData !== 'string') return false;
            if (imageData.startsWith('data:image')) return imageData.length > 100;
            if (imageData.startsWith('http://') || imageData.startsWith('https://') || imageData.startsWith('/')) return imageData.length > 10;
            if (imageData.startsWith('images/')) return true;
            return false;
        }

        function getValidCoverImage(article) {
            return (article.cover_image && isValidImage(article.cover_image)) ? article.cover_image : null;
        }

        function createNewsCard(article) {
            const title = article.title || '无标题';
            const summary = article.summary || article.content?.replace(/<[^>]*>/g, '').substring(0, 100) + '...' || '';
            const date = article.publishDate || article.created_at || new Date().toISOString();
            const formattedDate = new Date(date).toLocaleDateString('zh-CN');
            const coverImage = getValidCoverImage(article);
            const articleId = article.id;
            
            const imageHtml = coverImage 
                ? '<div class="news-thumb"><img src="' + coverImage + '" alt="' + title + '" loading="lazy"></div>'
                : '<div class="news-thumb placeholder"><div class="placeholder-bg"></div></div>';
            
            return '<article class="news-card">'
                + imageHtml
                + '<div class="news-content">'
                + '<h3><a href="news-detail.html?id=' + articleId + '">' + title + '</a></h3>'
                + '<p class="news-excerpt">' + summary + '</p>'
                + '<div class="news-footer">'
                + '<a href="news-detail.html?id=' + articleId + '" class="news-more">查看更多 &rarr;</a>'
                + '<time class="news-date">' + formattedDate + '</time>'
                + '</div></div></article>';
        }

        // 页面加载：先调API，再渲染
        document.addEventListener('DOMContentLoaded', async function() {
            const apiData = await loadAllFromServer();
            loadCategories(apiData);
            renderArticles(allArticlesCache);
        });
    </script>
    
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

