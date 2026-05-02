<?php
header('Content-Type: text/plain; charset=utf-8');

$adminDir = __DIR__ . '/admin';
$targetFiles = [
    'dashboard.html', 'case-management.html', 'index.html',
    'logo-settings.html', 'seo-settings.html', 'faq-management.html',
    'faq-edit.html', 'case-edit.html', 'case-edit-backup.html', 'nav-management.html'
];

// 错误的字节序列（"页脚管理"被错误编码的结果）
$wrongHex = "\xE6\xA4\xA4\xE4\xBD\x83\xE5\x89\xBC\xE7\xBB\xA0\xEF\xBC\x84\xE6\x82\x8A";
// 正确的UTF-8字节
$correct = '页脚管理';

$fixed = 0;
foreach ($targetFiles as $fn) {
    $path = $adminDir . '/' . $fn;
    if (!file_exists($path)) {
        echo "[SKIP] $fn 不存在\n";
        continue;
    }
    
    $content = file_get_contents($path);
    $orig = $content;
    
    // 二进制替换
    $content = str_replace($wrongHex, $correct, $content);
    
    if ($content !== $orig) {
        // 验证替换后是否包含正确文本
        if (strpos($content, $correct) !== false) {
            file_put_contents($path, "\xEF\xBB\xBF" . $content);
            $fixed++;
            echo "[OK] $fn 已修复\n";
        } else {
            echo "[FAIL] $fn 替换后验证失败\n";
        }
    } else {
        // 检查是否有其他编码的乱码
        $textPos = strpos($content, 'footer-manager.html');
        if ($textPos !== false) {
            $snip = substr($content, $textPos, 150);
            preg_match('/<span>([^<]*)<\/span>/', $snip, $m);
            $spanText = isset($m[1]) ? $m[1] : '(not found)';
            $isCorrect = ($spanText === $correct);
            echo "[$isCorrect] $fn span='$spanText'\n";
        } else {
            echo "[?] $fn 没有 footer-manager 链接\n";
        }
    }
}

echo "\n共修复 $fixed 个文件\n";
