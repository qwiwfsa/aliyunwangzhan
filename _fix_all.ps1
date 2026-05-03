function Fix-File($path, $isCase) {
    Write-Host "=== Processing: $path ==="
    $bytes = [System.IO.File]::ReadAllBytes($path)
    Write-Host "  Size: $($bytes.Length) bytes"
    
    # Try UTF-8 first
    $content = [System.Text.Encoding]::UTF8.GetString($bytes)
    $diamondCount = [regex]::Matches($content, "\uFFFD").Count
    
    if ($diamondCount -gt 0) {
        Write-Host "  UTF-8 has $diamondCount errors, trying GBK..."
        $gbk = [System.Text.Encoding]::GetEncoding(936)
        $content = $gbk.GetString($bytes)
        $gbkDiamond = [regex]::Matches($content, "\uFFFD").Count
        Write-Host "  GBK errors: $gbkDiamond"
    }
    
    # For case-detail.html: already has API fetching, just re-encode
    # For news-detail.html: replace localStorage with API
    
    if (-not $isCase) {
        Write-Host "  => Replacing localStorage JS with API loading JS..."
        
        # Replace the whole localStorage-based function block
        # The JS starts from "// 从localStorage加载数据" and has multiple functions
        $oldBlockStart = "// 从localStorage加载数据"
        $oldBlockEnd = "// 阅读计数"
        
        if ($content.Contains($oldBlockStart)) {
            Write-Host "  Found marker, replacing..."
            
            # Calculate positions
            $startIdx = $content.IndexOf($oldBlockStart)
            $endIdx = $content.IndexOf($oldBlockEnd)
            
            if ($endIdx -gt $startIdx) {
                # Build replacement JS block
                $apiBlock = @"
        // 从API加载数据
        async function loadArticleFromApi(articleId) {
            try {
                const response = await fetch('admin/api/news/list.php?t=' + Date.now(), { cache: 'no-store' });
                const result = await response.json();
                if (result.code === 0 && result.data && result.data.length > 0) {
                    const articles = result.data;
                    const article = articles.find(a => String(a.id) === String(articleId));
                    if (article) {
                        return { article: article, allArticles: articles };
                    }
                }
            } catch (error) {
                console.error('[News Detail] API加载失败:', error);
            }
            return null;
        }
        
        // 渲染文章头部
"@
                $before = $content.Substring(0, $startIdx)
                $after = $content.Substring($endIdx)
                $content = $before + $apiBlock + $after
                
                # Also remove the getAllPublishedArticles function (between loadArticleFromStorage and renderArticleHeader)
                # And remove everything between the marker and renderArticleHeader
                $marker1 = "// 从localStorage加载数据"
                $marker2 = "// 渲染文章头部"
                $idx1 = $content.IndexOf($marker1)
                $idx2 = $content.IndexOf($marker2)
                if ($idx1 -ge 0 -and $idx2 -gt $idx1) {
                    # Remove everything from marker1 to marker2 (inclusive of the old function, keep our new one)
                    $newApiBlockExactly = $content.Substring($idx1, ($idx2 - $idx1))
                    # This removed content should contain the localStorage functions we already replaced
                    # Let's verify - actually we already replaced above. Let's just check what's between
                    Write-Host "  Content between markers needs verification..."
                }
                
                Write-Host "  Block replaced successfully"
            }
        }
        
        # Now fix the DOMContentLoaded handler - change to async
        $content = $content -replace "document\.addEventListener\('DOMContentLoaded', function\(\) \{", "document.addEventListener('DOMContentLoaded', async function() {"
        $content = $content -replace "const article = loadArticleFromStorage\(articleId\);", "const result = await loadArticleFromApi(articleId);`r`n            const article = result ? result.article : null;`r`n            const articles = result ? result.allArticles : [];"
        $content = $content -replace "const allArticles = getAllPublishedArticles\(\);", "// allArticles already fetched from API"
        
        # Remove the old localStorage array fetch
        $pattern = "const articles = JSON\.parse\(localStorage\.getItem\('cms_articles'\) \|\| '\[\]'\);"
        $content = $content -replace [regex]::Escape($pattern), "// articles loaded from API above"
        
        # Also fix renderRelatedArticles call
        $content = $content -replace "renderRelatedArticles\(articleId, allArticles\);", "if (result) renderRelatedArticles(articleId, articles);"
        
        Write-Host "  JS modifications done"
    }
    
    # Save as UTF-8 with BOM
    $utf8Bytes = [System.Text.Encoding]::UTF8.GetBytes($content)
    $bom = [System.Text.Encoding]::UTF8.GetPreamble()
    [System.IO.File]::WriteAllBytes($path, $bom + $utf8Bytes)
    Write-Host "  Saved as UTF-8 BOM ($(($bom + $utf8Bytes).Length) bytes)"
    Write-Host ""
}

Fix-File "D:\yingyong\xampp\htdocs\hongdu\news-detail.html" $false
Fix-File "D:\yingyong\xampp\htdocs\hongdu\case-detail.html" $true

Write-Host "Done!"
