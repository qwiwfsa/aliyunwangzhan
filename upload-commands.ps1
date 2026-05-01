# 文件上传命令 - 在本地PowerShell执行
# 请将以下命令在本地电脑的PowerShell中执行

$SERVER_IP = "47.95.236.85"
$WEB_ROOT = "/www/wwwroot/api.yaozijin.com"
$LOCAL_PATH = "D:\OpenClaw\Data\.openclaw\workspace\zongjingli-laicai\website-capital-final"

Write-Host "========================================" -ForegroundColor Green
Write-Host "  步骤3：上传文件到阿里云服务器" -ForegroundColor Green
Write-Host "========================================" -ForegroundColor Green
Write-Host ""

# 注意：需要先配置SSH密钥或使用密码登录
# 如果使用密码，请安装 pscp 或使用 WinSCP/FileZilla

Write-Host "【方式A】使用SCP命令上传（需要配置SSH密钥）:" -ForegroundColor Yellow
Write-Host ""
Write-Host "# 上传admin目录"
Write-Host "scp -r `"$LOCAL_PATH\admin\`" root@${SERVER_IP}:${WEB_ROOT}/"
Write-Host ""
Write-Host "# 上传cms目录"
Write-Host "scp -r `"$LOCAL_PATH\cms\`" root@${SERVER_IP}:${WEB_ROOT}/"
Write-Host ""
Write-Host "# 上传server.js"
Write-Host "scp `"$LOCAL_PATH\server.js`" root@${SERVER_IP}:${WEB_ROOT}/"
Write-Host ""
Write-Host "# 上传部署脚本"
Write-Host "scp `"$LOCAL_PATH\deploy-steps-2-7.sh`" root@${SERVER_IP}:${WEB_ROOT}/"
Write-Host ""

Write-Host "【方式B】使用WinSCP或FileZilla（推荐Windows用户）:" -ForegroundColor Yellow
Write-Host ""
Write-Host "1. 打开WinSCP或FileZilla"
Write-Host "2. 连接服务器: ${SERVER_IP}"
Write-Host "3. 用户名: root"
Write-Host "4. 密码: [您的服务器密码]"
Write-Host "5. 上传到目录: ${WEB_ROOT}"
Write-Host ""
Write-Host "需要上传的文件/目录:"
Write-Host "  - admin/ (整个目录)"
Write-Host "  - cms/ (整个目录)"
Write-Host "  - server.js"
Write-Host "  - deploy-steps-2-7.sh"
Write-Host "  - nginx-config.conf"
Write-Host ""

Write-Host "========================================" -ForegroundColor Green
Write-Host "  上传完成后，在服务器执行:" -ForegroundColor Green
Write-Host "  bash ${WEB_ROOT}/deploy-steps-2-7.sh" -ForegroundColor Cyan
Write-Host "========================================" -ForegroundColor Green
