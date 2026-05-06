<?php header('Cache-Control: no-cache, no-store, must-revalidate'); ?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>测试 - 行业资讯</title>
</head>
<body>
    <h1>行业资讯 - 测试页</h1>
    <div id="articles"></div>
    <script>
    (async function() {
        try {
            const resp = await fetch('api/news.php?page=1&limit=20&t=' + Date.now());
            const text = await resp.text();
            const data = JSON.parse(text);
            
            let html = '<p>API返回成功：' + data.data.news.length + '篇文章</p><ul>';
            data.data.news.forEach(function(a) {
                html += '<li><b>' + (a.title || '无标题') + '</b> | 封面: ' + (a.cover_image || '无') + '</li>';
            });
            html += '</ul>';
            document.getElementById('articles').innerHTML = html;
        } catch(e) {
            document.getElementById('articles').innerHTML = '<div style="color:red;font-size:20px;">错误: ' + e.message + '</div>';
        }
    })();
    </script>
</body>
</html>
