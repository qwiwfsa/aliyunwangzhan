# Remove BOM from all files in the project
$patterns = @(
    'D:\yingyong\xampp\htdocs\hongdu\admin\api\*.php',
    'D:\yingyong\xampp\htdocs\hongdu\admin\*.php',
    'D:\yingyong\xampp\htdocs\hongdu\admin\*.js',
    'D:\yingyong\xampp\htdocs\hongdu\admin\assets\*.js',
    'D:\yingyong\xampp\htdocs\hongdu\css\*.css',
    'D:\yingyong\xampp\htdocs\hongdu\js\*.js',
    'D:\yingyong\xampp\htdocs\hongdu\*.php'
)
$fixedCount = 0
foreach ($pattern in $patterns) {
    Get-ChildItem -Path $pattern -ErrorAction SilentlyContinue | ForEach-Object {
        $path = $_.FullName
        [byte[]]$bytes = Get-Content -Path $path -Encoding Byte -Raw
        if ($bytes.Count -ge 3 -and $bytes[0] -eq 239 -and $bytes[1] -eq 187 -and $bytes[2] -eq 191) {
            [byte[]]$newBytes = $bytes[3..($bytes.Count - 1)]
            [System.IO.File]::WriteAllBytes($path, $newBytes)
            Write-Output "FIXED: $path"
            $fixedCount++
        }
    }
}
Write-Output "================================"
Write-Output "Total BOM files fixed: $fixedCount"
