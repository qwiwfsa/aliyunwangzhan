$path = "D:\yingyong\xampp\htdocs\hongdu\tablet\index.html.backup.20260504094445"
[byte[]]$bytes = Get-Content -Path $path -Encoding Byte -TotalCount 3
if ($bytes.Count -ge 3 -and $bytes[0] -eq 239 -and $bytes[1] -eq 187 -and $bytes[2] -eq 191) {
    Write-Output "BOM"
} else {
    Write-Output "NO BOM"
}
