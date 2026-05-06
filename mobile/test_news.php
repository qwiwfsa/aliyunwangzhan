<?php
// 直接内联所有JS和HTML，绕过所有缓存
header('Cache-Control: no-cache, no-store, must-revalidate');
header('Pragma: no-cache');
header('Expires: 0');
?><!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=5.0">
    <title>行业资讯 - 测试页面</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="../css/style.css">
    <style>
        .news-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
            gap: 20px;
            padding: 20px;
        }
        .news-card {
            background: #fff;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 2px 10px rgba(0,0,0,0.08);
        }
        .news-card img {
            width: 100%;
            height: 180px;
            object-fit: cover;
        }
        .news-card .card-body {
            padding: 15px;
        }
        .news-card .card-body h3 {
            font-size: 16px;
            margin: 0 0 8px 0;
            line-height: 1.4;
        }
        .news-card .card-body p {
            font-size: 13px;
            color: #666;
            margin: 0;
            line-height: 1.6;
        }
        .news-card .card-meta {
            font-size: 12px;
            color: #999;
            padding: 10px 15px;
            border-top: 1px solid #eee;
        }
    </style>
</head>
<body>
    <nav class="navbar scrolled" id="navbar">
        <div class="navbar-container">
            <a href="index.html" class="logo"><img src="../uploads/logo/logo_20260501_234314_69f51e721c7d0.png" alt="Yao" style="height:48px;"></a>
            <ul class="nav-menu"><li><a href="index.html">首页</a></li><li><a href="services.html">业务范围</a></li><li><a href="news.php" class="active">行业资讯</a></li></ul>
        </div>
    </nav>

    <section class="page-banner">
        <div class="banner-content">
            <h1>行业资讯</h1>
            <p>了解最新金融动态与行业政策</p>
        </div>
    </section>

    <section class="section-container" style="max-width:1200px;margin:0 auto;padding:20px;">
        <div id="newsCategories" style="display:flex;gap:10px;flex-wrap:wrap;margin-bottom:20px;">
            <button class="filter-btn active" data-category="0">全部资讯</button>
        </div>
        <div class="news-grid" id="newsContainer"></div>
        <div class="news-pagination" id="pagination" style="display:flex;justify-content:center;gap:10px;margin:30px 0;">
            <button id="prevBtn" style="padding:8px 16px;">上一页</button>
            <span id="pageInfo" style="padding:8px 16px;">第 1 页</span>
            <button id="nextBtn" style="padding:8px 16px;">下一页</button>
        </div>
    </section>

    <script>
    let allArticles = [];
    let currentPage = 1;
    let totalPages = 1;
    const pageSize = 10;
    let currentCategory = 0;
    
    async function loadArticles() {
        const url = '/hongdu/mobile/api/news.php?page=' + currentPage + '&limit=' + pageSize + '&category=' + currentCategory + '&t=' + Date.now();
        try {
            const resp = await fetch(url);
            const result = await resp.json();
            
            if (result.success && result.data && result.data.news) {
                allArticles = result.data.news;
                totalPages = result.data.totalPages || 1;
                renderArticles();
                renderCategories(result.data.categories);
                renderPagination();
                
                // 显示调试信息
                document.getElementById('debugInfo').textContent = '成功加载 ' + allArticles.length + ' 篇';
            } else {
                document.getElementById('debugInfo').textContent = 'API返回异常: ' + JSON.stringify(result);
            }
        } catch(e) {
            document.getElementById('debugInfo').textContent = '加载失败: ' + e.message;
        }
    }
    
    function renderArticles() {
        const container = document.getElementById('newsContainer');
        container.innerHTML = '';
        
        if (!allArticles || allArticles.length === 0) {
            container.innerHTML = '<div style="text-align:center;padding:40px;color:#999;">暂无文章</div>';
            return;
        }
        
        allArticles.forEach(article => {
            const cover = article.cover_image ? 'http://localhost' + article.cover_image : '';
            const card = document.createElement('div');
            card.className = 'news-card';
            card.innerHTML = `
                <img src="${cover}" alt="${article.title || ''}" onerror="this.style.display='none'">
                <div class="card-body">
                    <h3>${article.title || '无标题'}</h3>
                    <p>${article.summary || article.content ? (article.summary || article.content).substring(0, 120) : ''}...</p>
                </div>
                <div class="card-meta">
                    <span>${article.created_at || ''}</span>
                    <span style="float:right">${article.category_name || ''}</span>
                </div>
            `;
            container.appendChild(card);
        });
    }
    
    function renderCategories(categories) {
        if (!categories) return;
        const container = document.getElementById('newsCategories');
        container.innerHTML = '<button class="filter-btn' + (currentCategory === 0 ? ' active' : '') + '" data-category="0">全部资讯</button>';
        categories.forEach(cat => {
            container.innerHTML += '<button class="filter-btn' + (currentCategory == cat.id ? ' active' : '') + '" data-category="' + cat.id + '">' + (cat.name || cat.category_name || '未知') + '</button>';
        });
        
        container.querySelectorAll('.filter-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                currentCategory = parseInt(this.dataset.category);
                currentPage = 1;
                loadArticles();
            });
        });
    }
    
    function renderPagination() {
        document.getElementById('pageInfo').textContent = '第 ' + currentPage + ' / ' + totalPages + ' 页';
        document.getElementById('prevBtn').onclick = function() {
            if (currentPage > 1) { currentPage--; loadArticles(); }
        };
        document.getElementById('nextBtn').onclick = function() {
            if (currentPage < totalPages) { currentPage++; loadArticles(); }
        };
    }
    
    loadArticles();
    </script>
    
    <div id="debugInfo" style="position:fixed;bottom:0;left:0;right:0;background:#333;color:#0f0;padding:10px;font-size:14px;z-index:9999;">
        加载中...
    </div>
</body>
</html>
