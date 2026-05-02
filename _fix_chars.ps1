$adminDir = "D:\yingyong\xampp\htdocs\hongdu\admin"
$targetFiles = @("dashboard.html","case-management.html","index.html","logo-settings.html","seo-settings.html","faq-management.html","faq-edit.html","case-edit.html","case-edit-backup.html","nav-management.html")

$correctBytes = [byte[]]@(0xE9, 0xA1, 0xB5, 0xE8, 0x84, 0x9A, 0xE7, 0xAE, 0xA1, 0xE7, 0x90, 0x86)
$wrongBytes = [byte[]]@(0xE6, 0xA4, 0xA4, 0xE4, 0xBD, 0x83, 0xE5, 0x89, 0xBC, 0xE7, 0xBB, 0xA0, 0xEF, 0xBC, 0x84, 0xE6, 0x82, 0x8A)

$fixed = 0
foreach ($fn in $targetFiles) {
    $path = Join-Path $adminDir $fn
    $bytes = [System.IO.File]::ReadAllBytes($path)
    $origLen = $bytes.Length
    $modified = $false
    
    $i = 0
    while ($i -le ($bytes.Length - $wrongBytes.Length)) {
        $match = $true
        for ($j = 0; $j -lt $wrongBytes.Length; $j++) {
            if ($bytes[$i + $j] -ne $wrongBytes[$j]) { $match = $false; break }
        }
        if ($match) {
            $newBytes = New-Object System.Collections.ArrayList
            for ($k = 0; $k -lt $i; $k++) { [void]$newBytes.Add($bytes[$k]) }
            for ($k = 0; $k -lt $correctBytes.Length; $k++) { [void]$newBytes.Add($correctBytes[$k]) }
            for ($k = $i + $wrongBytes.Length; $k -lt $bytes.Length; $k++) { [void]$newBytes.Add($bytes[$k]) }
            $bytes = $newBytes
            $i += $correctBytes.Length
            $modified = $true
        } else {
            $i++
        }
    }
    
    if ($modified) {
        $bom = @(0xEF, 0xBB, 0xBF)
        [System.IO.File]::WriteAllBytes($path, $bom + $bytes)
        $fixed++
        Write-Output ("修复 " + $fn + " (" + $origLen + " -> " + $bytes.Length + " 字节)")
    }
}

Write-Output ("共修复 " + $fixed + " 个文件")

# 验证
foreach ($fn in $targetFiles) {
    $path = Join-Path $adminDir $fn
    $bytes = [System.IO.File]::ReadAllBytes($path)
    $text = [System.Text.Encoding]::UTF8.GetString($bytes)
    
    $fmIdx = $text.IndexOf('footer-manager.html')
    if ($fmIdx -ge 0) {
        $afterFm = $text.Substring($fmIdx, 200)
        $sm = [regex]::Match($afterFm, '<span>([^<]*)</span>')
        if ($sm.Success) {
            $txt = $sm.Groups[1].Value
            $ok = ($txt -eq "页脚管理")
            Write-Output ($fn + " => '" + $txt + "' 正确=" + $ok)
        }
    }
}
