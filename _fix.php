<?php
/**
 * 编码修复脚本 - 将 news-detail.html 和 case-detail.html 从 GBK 转为 UTF-8
 * 并修改 news-detail.html 从 localStorage 改为 API 加载
 */

function fix_file($path, $is_case = false) {
    echo "=== Processing: $path ===\n";
    
    $raw = file_get_contents($path);
    echo "  Size: " . strlen($raw) . " bytes\n";
    
    // Try to detect encoding
    $utf8 = @mb_convert_encoding($raw, 'UTF-8', 'UTF-8');
    $has_errors = (strpos($utf8, "\u{FFFD}") !== false) || (strpos($utf8, '锟?') !== false);
    
    if (!$has_errors) {
        echo "  File appears to be valid UTF-8\n";
        // But check if Chinese chars are correct
        // Look for a known Chinese string
    }
    
    // Try GBK
    $gbk_content = @mb_convert_encoding($raw, 'UTF-8', 'GBK');
    if ($gbk_content !== false && $gbk_content !== '') {
        // Check if GBK produces readable Chinese
        $sample = substr($gbk_content, 0, 500);
        echo "  GBK sample: " . $sample . "\n";
        
        // Check if GBK version looks correct (contains Chinese chars that make sense)
        // The HTML should have Chinese like "文章详情" in comment blocks
        if (preg_match('/[\x{4e00}-\x{9fff}]/u', $gbk_content)) {
            echo "  GBK decoding produces Chinese characters - file was GBK encoded\n";
            $raw = $gbk_content;
        } else {
            echo "  GBK decoding did not produce Chinese - keeping as-is\n";
            // Try UTF-8 decode (the raw bytes might already be UTF-8 but with some corruption)
            $raw = $utf8;
        }
    }
    
    // For news-detail.html: replace localStorage JS with API loading
    if (!$is_case) {
        echo "  Modifying news-detail.html JS...\n";
        
        // Step 1: Replace the localStorage functions
        $local_storage_marker = '// 从localStorage加载数据';
        $render_header_marker = '// 渲染文章头部';
        
        $pos1 = strpos($raw, $local_storage_marker);
        $pos2 = strpos($raw, $render_header_marker);
        
        if ($pos1 !== false && $pos2 !== false && $pos2 > $pos1) {
            $replacement = '// 从API加载数据
        async function loadArticleFromApi(articleId) {
            try {
                const response = await fetch(\'admin/api/news/list.php?t=\' + Date.now(), {
                    cache: \'no-store\'
                });
                const result = await response.json();
                console.log(\'[News Detail] API响应:\', result);
                
                if (result.code === 0 && result.data && result.data.length > 0) {
                    const allArticles = result.data;
                    const article = allArticles.find(a => String(a.id) === String(articleId));
                    console.log(\'[News Detail] 找到文章:\', article);
                    if (article) {
                        return { article: article, allArticles: allArticles };
                    }
                }
                
                console.error(\'[News Detail] 未找到ID为\', articleId, \'的文章\');
                return null;
            } catch (error) {
                console.error(\'[News Detail] API加载失败:\', error);
            }
            return null;
        }
        
        // 渲染文章头部';
            $raw = substr_replace($raw, $replacement, $pos1, $pos2 - $pos1);
            echo "  Replaced localStorage functions with API functions\n";
        }
        
        // Step 2: Remove the getAllPublishedArticles function (between renderArticleHeader and renderArticleContent)
        $get_all_pub_marker = '// 获取已发布文章列表';
        $render_content_marker = '// 渲染文章内容';
        
        $pos1 = strpos($raw, $get_all_pub_marker);
        $pos2 = strpos($raw, $render_content_marker);
        
        if ($pos1 !== false && $pos2 !== false && $pos2 > $pos1) {
            $replacement = '// 渲染文章内容';
            $raw = substr_replace($raw, $replacement, $pos1, $pos2 - $pos1);
            echo "  Removed getAllPublishedArticles function\n";
        }
        
        // Step 3: Make DOMContentLoaded async and replace localStorage calls
        $raw = str_replace(
            "document.addEventListener('DOMContentLoaded', function() {",
            "document.addEventListener('DOMContentLoaded', async function() {",
            $raw
        );
        
        $raw = str_replace(
            "const article = loadArticleFromStorage(articleId);",
            "const result = await loadArticleFromApi(articleId);\n            const article = result ? result.article : null;\n            const allArticles = result ? result.allArticles : [];",
            $raw
        );
        
        // Remove the old localStorage articles fetch
        $old_fetch_pattern = "const articles = JSON.parse(localStorage.getItem('cms_articles') || '[]');";
        $raw = str_replace($old_fetch_pattern, "// articles now loaded via API", $raw);
        
        // Remove old getAllArticles call
        $raw = str_replace(
            "const allArticles = getAllPublishedArticles();",
            "// articles already fetched from API",
            $raw
        );
        
        // Update incrementViews to use API
        $raw = str_replace(
            "incrementViews(articleId);",
            "// incrementViews now skipped, can add API call later\n            incrementViews(articleId);",
            $raw
        );
        
        echo "  JS modifications complete\n";
    } else {
        echo "  Case-detail already has API loading, no JS changes needed\n";
    }
    
    // Save as UTF-8 with BOM
    $bom = "\xEF\xBB\xBF";
    $utf8_content = $bom . $raw;
    file_put_contents($path, $utf8_content);
    echo "  Saved as UTF-8 with BOM (" . strlen($utf8_content) . " bytes)\n";
    echo "\n";
}

fix_file('D:\\yingyong\\xampp\\htdocs\\hongdu\\news-detail.html', false);
fix_file('D:\\yingyong\\xampp\\htdocs\\hongdu\\case-detail.html', true);

echo "All fixes complete!\n";
