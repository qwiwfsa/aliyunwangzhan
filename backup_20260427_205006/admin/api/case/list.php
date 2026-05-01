<?php
/**
 * 案例列表API
 * 获取所有案例列表
 */

// 设置响应头
header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

// 处理预检请求
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

// 数据目录
$dataDir = __DIR__ . '/../../data/cases/';
$indexFile = __DIR__ . '/../../data/cases-index.json';

$cases = [];

// 优先从索引文件读取
if (file_exists($indexFile)) {
    $json = file_get_contents($indexFile);
    $cases = json_decode($json, true);
    if (!is_array($cases)) {
        $cases = [];
    }
} else {
    // 从单个案例文件读取
    if (file_exists($dataDir)) {
        $files = glob($dataDir . '*.json');
        foreach ($files as $file) {
            $json = file_get_contents($file);
            $caseData = json_decode($json, true);
            if ($caseData) {
                $cases[] = [
                    'id' => $caseData['id'],
                    'title' => $caseData['title'],
                    'type' => $caseData['type'],
                    'city' => $caseData['city'],
                    'amount' => $caseData['amount'],
                    'image' => $caseData['image'],
                    'status' => $caseData['status'],
                    'lastModified' => $caseData['lastModified']
                ];
            }
        }
    }
}

// 按最后修改时间排序
usort($cases, function($a, $b) {
    return strtotime($b['lastModified']) - strtotime($a['lastModified']);
});

echo json_encode([
    'success' => true,
    'cases' => $cases,
    'total' => count($cases)
]);
