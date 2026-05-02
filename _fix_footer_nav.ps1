$adminDir = "D:\yingyong\xampp\htdocs\hongdu\admin"
$files = @("dashboard.html","case-management.html","index.html","logo-settings.html","seo-settings.html","faq-management.html","faq-edit.html","case-edit.html","case-edit-backup.html","nav-management.html")

$footerLink = "`r`n                    <a href=`"footer-manager.html`" class=`"cms-nav-item`" data-section=`"footer`">" + "`r`n                        <i class=`"fas fa-list-alt`"></i>" + "`r`n                        <span>页脚管理</span>" + "`r`n                    </a>"

foreach ($f in $files) {
    $path = Join-Path $adminDir $f
    if (-not (Test-Path $path)) { 
        Write-Output ("跳过 " + $f + " (不存在)") 
        continue 
    }
    
    $bytes = [System.IO.File]::ReadAllBytes($path)
    $origContent = [System.Text.Encoding]::UTF8.GetString($bytes)
    
    # 删除所有 footer-manager 链接
    $cleaned = [regex]::Replace($origContent, '<a[^>]*href="footer-manager\.html[^>]*>[\s\S]{0,200}?</a>', '')
    
    $faqIdx = $cleaned.IndexOf('faq-management.html')
    if ($faqIdx -ge 0) {
        $endIdx = $cleaned.IndexOf('</a>', $faqIdx)
        if ($endIdx -ge 0) {
            $newContent = $cleaned.Substring(0, $endIdx + 4) + $footerLink + $cleaned.Substring($endIdx + 4)
        } else {
            $newContent = $cleaned + $footerLink
        }
    } else {
        $newContent = $cleaned + $footerLink
    }
    
    $outBytes = [System.Text.Encoding]::UTF8.GetBytes($newContent)
    $bom = @(0xEF, 0xBB, 0xBF)
    [System.IO.File]::WriteAllBytes($path, $bom + $outBytes)
    
    $vBytes = [System.IO.File]::ReadAllBytes($path)
    $verify = [System.Text.Encoding]::UTF8.GetString($vBytes)
    $hasFooter = $verify.IndexOf("页脚管理") -ge 0
    $hasHref = $verify.IndexOf("footer-manager.html") -ge 0
    
    if ($hasFooter -and $hasHref) {
        Write-Output ("✅ " + $f + " 修复成功")
    } else {
        Write-Output ("❌ " + $f + " 修复失败: footer=" + $hasFooter + " href=" + $hasHref)
    }
}
