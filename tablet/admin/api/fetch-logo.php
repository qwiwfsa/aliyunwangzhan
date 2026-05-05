<?php
/**
 * 平板端 - 获取Logo设置API
 * 从主站data/logo-settings.json读取，若无则返回硬编码默认值
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

// 数据文件路径（引用主站数据）
$dataFile = dirname(__DIR__, 3) . '/data/logo-settings.json';

// 默认Logo设置
$defaults = [
    'header_logo' => 'uploads/logo/logo_20260501_234314_69f51e721c7d0.png',
    'footer_logo' => 'uploads/logo/logo_20260502_190529_69f62ed969290.png',
    'favicon' => 'uploads/logo/logo_20260504_045101_69f80995372b0.png',
    'admin_logo' => 'uploads/logo/logo_20260501_234314_69f51e721c7d0.png',
    'updated_at' => ''
];

$settings = $defaults;

if (file_exists($dataFile)) {
    $content = file_get_contents($dataFile);
    $parsed = json_decode($content, true);
    if (json_last_error() === JSON_ERROR_NONE && is_array($parsed)) {
        $settings = array_merge($defaults, $parsed);
    }
}

// 处理路径：去掉 ../ 前缀
$pathFields = ['header_logo', 'footer_logo', 'favicon', 'admin_logo'];
foreach ($pathFields as $field) {
    if (!empty($settings[$field])) {
        $path = $settings[$field];
        $path = str_replace('\\', '/', $path);
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
