# Fix paths in tablet HTML files - replace relative paths for correct tablet subdirectory
$tabletDir = "D:\yingyong\xampp\htdocs\hongdu\tablet"

# Files to fix (all except applications.html which was wrong anyway)
$files = @("advantages.html", "case-detail.html", "cases.html", "compliance.html", 
           "contact.html", "faq.html", "index.html", "news-detail.html", "news.html", "services.html")

$replacements = @(
    # CSS path: css/ -> ../css/ (but NOT cdn links)
    @('href="css/', 'href="../css/'),
    # JS path: js/ -> ../js/
    @('src="js/', 'src="../js/'),
    # Admin API: admin/api/ -> ../admin/api/ (but NOT already ../admin/)
    @('src="admin/', 'src="../admin/'),
    # Uploads: ./uploads/ or "uploads/ -> ../uploads/
    @('"./uploads/', '"../uploads/'),
    # Images: "images/ -> "../images/
    @('"images/', '"../images/'),
    # API: "api/ -> "../api/
    @('href="api/', 'href="../api/'),
    # fetch(api/ -> fetch('../api/
    @('fetch(api/', 'fetch(../api/'),
    # Fetch relative in script: fetch("../api/ -> already ok
)

foreach ($file in $files) {
    $path = Join-Path $tabletDir $file
    if (-not (Test-Path $path)) {
        Write-Output "SKIP (not found): $file"
        continue
    }
    
    $content = Get-Content -Path $path -Raw
    $changed = $false
    
    foreach ($pair in $replacements) {
        $old = $pair[0]
        $new = $pair[1]
        if ($content.Contains($old)) {
            $content = $content.Replace($old, $new)
            $changed = $true
        }
    }
    
    if ($changed) {
        $utf8NoBom = New-Object System.Text.UTF8Encoding $false
        [System.IO.File]::WriteAllText($path, $content, $utf8NoBom)
        Write-Output "FIXED: $file"
    } else {
        Write-Output "NO CHANGE: $file"
    }
}

Write-Output "=== DONE ==="
