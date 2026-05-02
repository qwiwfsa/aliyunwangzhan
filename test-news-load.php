<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>娴嬭瘯鏂囩珷鍔犺浇</title>
    <style>
        body { font-family: Arial, sans-serif; padding: 20px; max-width: 800px; margin: 0 auto; }
        .test-section { margin: 20px 0; padding: 15px; border: 1px solid #ddd; border-radius: 8px; }
        .success { color: green; }
        .error { color: red; }
        .info { color: blue; }
        button { padding: 10px 20px; margin: 5px; cursor: pointer; }
        pre { background: #f5f5f5; padding: 10px; overflow-x: auto; }
    </style>
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
    <h1>鏂囩珷鍙戝竷涓庡姞杞芥祴璇?/h1>
    
    <div class="test-section">
        <h2>1. 鍒涘缓娴嬭瘯鏂囩珷</h2>
        <button onclick="createTestArticle()">鍒涘缓娴嬭瘯鏂囩珷</button>
        <div id="createResult"></div>
    </div>
    
    <div class="test-section">
        <h2>2. 鏌ョ湅localStorage涓�鐨勬枃绔�</h2>
        <button onclick="showArticles()">鏄剧ず鎵�鏈夋枃绔?/button>
        <pre id="articlesDisplay"></pre>
    </div>
    
    <div class="test-section">
        <h2>3. 妯℃嫙news.html鍔犺浇閫昏緫</h2>
        <button onclick="testNewsLoad()">娴嬭瘯鍔犺浇閫昏緫</button>
        <div id="loadResult"></div>
    </div>
    
    <div class="test-section">
        <h2>4. 娓呯┖娴嬭瘯鏁版嵁</h2>
        <button onclick="clearArticles()">娓呯┖鎵�鏈夋枃绔?/button>
    </div>

    <script>
        // 鍒涘缓娴嬭瘯鏂囩珷
        function createTestArticle() {
            const now = new Date().toISOString();
            const testArticle = {
                id: Date.now(),
                title: '娴嬭瘯鏂囩珷 - ' + new Date().toLocaleString(),
                summary: '杩欐槸涓�绡囨祴璇曟枃绔狅紝鐢ㄤ簬楠岃瘉鍙戝竷鍔熻兘鏄�鍚︽�ｅ父銆?,
                content: '<p>娴嬭瘯鏂囩珷鍐呭��...</p>',
                category_id: 1,
                cover_image: 'images/news/news-1.jpg',
                status: 'published',
                is_top: 0,
                sort_order: 0,
                created_at: now,
                updated_at: now,
                publishDate: now
            };
            
            let articles = JSON.parse(localStorage.getItem('cms_articles') || '[]');
            articles.push(testArticle);
            localStorage.setItem('cms_articles', JSON.stringify(articles));
            
            document.getElementById('createResult').innerHTML = 
                '<p class="success">鉁?娴嬭瘯鏂囩珷鍒涘缓鎴愬姛锛両D: ' + testArticle.id + '</p>';
        }
        
        // 鏄剧ず鎵�鏈夋枃绔?        function showArticles() {
            const articles = JSON.parse(localStorage.getItem('cms_articles') || '[]');
            document.getElementById('articlesDisplay').textContent = JSON.stringify(articles, null, 2);
        }
        
        // 娴嬭瘯鍔犺浇閫昏緫
        function testNewsLoad() {
            console.log('[News] 寮�濮嬪姞杞借祫璁�鏂囩�?..');
            
            const articles = JSON.parse(localStorage.getItem('cms_articles') || '[]');
            console.log('[News] 鏈�鍦板瓨鍌ㄦ枃绔犳暟閲�:', articles.length);
            
            const publishedArticles = articles.filter(a => a.status === 'published');
            console.log('[News] 宸插彂甯冩枃绔犳暟閲?', publishedArticles.length);
            
            if (publishedArticles.length === 0) {
                document.getElementById('loadResult').innerHTML = 
                    '<p class="error">鉁?娌℃湁鎵惧埌宸插彂甯冪殑鏂囩珷</p>';
                return;
            }
            
            // 鎸夋棩鏈熸帓搴?            publishedArticles.sort((a, b) => {
                const dateA = new Date(a.publishDate || a.created_at || 0);
                const dateB = new Date(b.publishDate || b.created_at || 0);
                return dateB - dateA;
            });
            
            let html = '<p class="success">鉁?鎴愬姛鍔犺浇 ' + publishedArticles.length + ' 绡囧凡鍙戝竷鏂囩珷</p>';
            html += '<ul>';
            publishedArticles.forEach(article => {
                html += '<li>ID: ' + article.id + ' - ' + article.title + 
                        ' (鐘舵�? ' + article.status + ', 鍙戝竷鏃ユ湡: ' + (article.publishDate || '鏃?) + ')</li>';
            });
            html += '</ul>';
            
            document.getElementById('loadResult').innerHTML = html;
        }
        
        // 娓呯┖鏂囩珷
        function clearArticles() {
            localStorage.removeItem('cms_articles');
            document.getElementById('articlesDisplay').textContent = '';
            document.getElementById('createResult').innerHTML = '';
            document.getElementById('loadResult').innerHTML = '';
            alert('鎵�鏈夋枃绔犲凡娓呯┖');
        }
        
        // 椤甸潰鍔犺浇鏃舵樉绀虹幇鏈夋枃绔?        showArticles();
    </script>
</body>
</html>
