<?php
/**
 * 前台获取Logo设置API
 * 只读取logo-settings.json返回JSON数据
 */

// CORS 跨域头
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');
header('Content-Type: application/json; charset=utf-8');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(204);
    exit;
}

// 数据文件路径
$dataFile = dirname(__DIR__) . '/data/logo-settings.json';

// 默认Logo设置
$defaults = [
    'header_logo' => 'images/logo.png',
    'footer_logo' => 'images/logo.png',
    'favicon' => 'images/favicon.ico',
    'admin_logo' => 'images/logo.png',
    'updated_at' => ''
];

if (!file_exists($dataFile)) {
    echo json_encode([
        'code' => 0,
        'msg' => 'success',
        'data' => $defaults
    ], JSON_UNESCAPED_UNICODE);
    exit;
}

$content = file_get_contents($dataFile);
$settings = json_decode($content, true);

if (json_last_error() !== JSON_ERROR_NONE || !is_array($settings)) {
    $settings = $defaults;
}

// 统一处理路径：去掉 ../ 前缀，确保前台页面能正确解析路径
$pathFields = ['header_logo', 'footer_logo', 'favicon', 'admin_logo'];
foreach ($pathFields as $field) {
    if (!empty($settings[$field])) {
        $path = $settings[$field];
        // 替换反斜杠为正斜杠
        $path = str_replace('\\', '/', $path);
        // 去掉 ../ 前缀
        if (strpos($path, '../') === 0) {
            $path = '.' . substr($path, 2);
        }
        $settings[$field] = $path;
    }
}

echo json_encode([
    'code' => 0,
    'msg' => 'success',
    'data' => $settings
], JSON_UNESCAPED_UNICODE);
