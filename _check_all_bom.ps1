# Check BOM on all CSS, JS, PHP files
$targets = @(
    'D:\yingyong\xampp\htdocs\hongdu\admin\api\*.php',
    'D:\yingyong\xampp\htdocs\hongdu\admin\*.php',
    'D:\yingyong\xampp\htdocs\hongdu\admin\*.js',
    'D:\yingyong\xampp\htdocs\hongdu\admin\assets\*.js',
    'D:\yingyong\xampp\htdocs\hongdu\css\*.css',
    'D:\yingyong\xampp\htdocs\hongdu\js\*.js',
    'D:\yingyong\xampp\htdocs\hongdu\*.php'
)
foreach ($pattern in $targets) {
    $files = Get-ChildItem -Path $pattern -ErrorAction SilentlyContinue
    foreach ($f in $files) {
        $bytes = Get-Content -Path $f.FullName -Encoding Byte -TotalCount 3
        if ($bytes.Count -ge 3 -and $bytes[0] -eq 239 -and $bytes[1] -eq 187 -and $bytes[2] -eq 191) {
            Write-Output "BOM: $($f.FullName)"
        }
    }
}
Write-Output "=== DONE ==="
