param(
    [string]$Path = "D:\yingyong\xampp\htdocs\hongdu"
)

function Check-BOM {
    param([string]$FilePath)
    $bytes = [System.IO.File]::ReadAllBytes($FilePath)
    if ($bytes.Length -ge 3 -and $bytes[0] -eq 0xEF -and $bytes[1] -eq 0xBB -and $bytes[2] -eq 0xBF) {
        return $true
    }
    return $false
}

function Remove-BOM {
    param([string]$FilePath)
    $bytes = [System.IO.File]::ReadAllBytes($FilePath)
    if ($bytes.Length -ge 3 -and $bytes[0] -eq 0xEF -and $bytes[1] -eq 0xBB -and $bytes[2] -eq 0xBF) {
        $newBytes = $bytes[3..($bytes.Length - 1)]
        [System.IO.File]::WriteAllBytes($FilePath, $newBytes)
        return $true
    }
    return $false
}

$extensions = @("*.css", "*.js", "*.html", "*.php", "*.json", "*.xml", "*.htaccess")
$excludeDirs = @("wp-admin", "wp-content", "wp-includes", "node_modules", "vendor", "backup_20260502", "hognduziben-main")

Write-Host "===== BOM CHECK REPORT =====" -ForegroundColor Cyan
Write-Host "Path: $Path`n" -ForegroundColor Cyan

$foundBOM = @()

Get-ChildItem -Path $Path -Recurse -Include $extensions | ForEach-Object {
    $relPath = $_.FullName.Replace($Path, "")
    $shouldExclude = $false
    
    # Check if file is in excluded directories
    foreach ($exDir in $excludeDirs) {
        if ($_.FullName -match [regex]::Escape($exDir)) {
            $shouldExclude = $true
            break
        }
    }
    
    # Also check if it's in wp-* or admin/api (only include project files)
    if ($shouldExclude) {
        return
    }
    
    if (Check-BOM -FilePath $_.FullName) {
        Write-Host "BOM: $relPath" -ForegroundColor Red
        $foundBOM += $_.FullName
    }
}

Write-Host "`n===== RESULTS =====" -ForegroundColor Cyan
if ($foundBOM.Count -eq 0) {
    Write-Host "No BOM found in any file. ✓" -ForegroundColor Green
} else {
    Write-Host "Found $($foundBOM.Count) file(s) with BOM." -ForegroundColor Yellow
    
    $confirm = Read-Host "Remove BOM from all listed files? (y/n)"
    if ($confirm -eq "y") {
        $removed = 0
        foreach ($file in $foundBOM) {
            if (Remove-BOM -FilePath $file) {
                Write-Host "  Removed BOM: $($file.Replace($Path,''))" -ForegroundColor Green
                $removed++
            }
        }
        Write-Host "`nRemoved BOM from $removed files. ✓" -ForegroundColor Green
    }
}

Write-Host "`n===== CHECK ENCODING =====" -ForegroundColor Cyan
Write-Host "Checking key files for encoding issues..." -ForegroundColor Cyan

# Check specific important files
$keyFiles = @(
    "css/style.css",
    "css/page-custom.css",
    "css/cases-enhanced.css",
    "js/main.js",
    "tablet/",
    "admin/api/"
)

foreach ($kf in $keyFiles) {
    $fullPath = Join-Path $Path $kf
    if (Test-Path $fullPath) {
        if ((Get-Item $fullPath) -is [System.IO.DirectoryInfo]) {
            Get-ChildItem $fullPath -Include *.html,*.php,*.js,*.css | ForEach-Object {
                if (Check-BOM -FilePath $_.FullName) {
                    Write-Host "BOM: $($_.FullName.Replace($Path,''))" -ForegroundColor Red
                }
            }
        } else {
            if (Check-BOM -FilePath $fullPath) {
                Write-Host "BOM: $kf" -ForegroundColor Red
            } else {
                Write-Host "OK: $kf" -ForegroundColor Green
            }
        }
    } else {
        Write-Host "NOT FOUND: $kf" -ForegroundColor Gray
    }
}
