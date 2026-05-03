# Fix encoding and JS for news-detail.html and case-detail.html

# Read the raw bytes of each file
$htmlFiles = @(
    "D:\yingyong\xampp\htdocs\hongdu\news-detail.html",
    "D:\yingyong\xampp\htdocs\hongdu\case-detail.html"
)

foreach ($path in $htmlFiles) {
    Write-Host "Processing: $path"
    $bytes = [System.IO.File]::ReadAllBytes($path)
    Write-Host "  File size: $($bytes.Length) bytes"
    
    # Check if file has UTF-8 BOM
    $bom = [System.Text.Encoding]::UTF8.GetPreamble()
    $hasBom = $true
    if ($bytes.Length -ge $bom.Length) {
        for ($i = 0; $i -lt $bom.Length; $i++) {
            if ($bytes[$i] -ne $bom[$i]) { $hasBom = $false; break }
        }
    } else {
        $hasBom = $false
    }
    Write-Host "  Has UTF-8 BOM: $hasBom"
    
    # Try to read as UTF-8 and check for replacement characters
    $contentUtf8 = [System.Text.Encoding]::UTF8.GetString($bytes)
    $countDiamond = [regex]::Matches($contentUtf8, "\uFFFD").Count
    Write-Host "  UTF-8 replacement char count: $countDiamond"
    
    # Try to read as GB2312 (codepage 936)
    $encGb = [System.Text.Encoding]::GetEncoding(936)
    $contentGb = $encGb.GetString($bytes)
    $countGbDiamond = [regex]::Matches($contentGb, "\uFFFD").Count
    Write-Host "  GB2312 replacement char count: $countGbDiamond"
    
    # Sample the content to see which encoding produces readable Chinese
    Write-Host "  UTF-8 sample (first 100 chars):"
    Write-Host "    $($contentUtf8.Substring(0, [Math]::Min(100, $contentUtf8.Length)))"
    Write-Host "  GB2312 sample (first 100 chars):"
    Write-Host "    $($contentGb.Substring(0, [Math]::Min(100, $contentGb.Length)))"
    Write-Host ""
}
