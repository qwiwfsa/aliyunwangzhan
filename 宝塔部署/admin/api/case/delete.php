<?php
/**
 * 案例删除API
 * 删除案例数据
 */

// 设置响应头
header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

// 处理预检请求
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

// 获取案例ID
$caseId = '';
if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
    parse_str(file_get_contents('php://input'), $data);
    $caseId = isset($data['id']) ? $data['id'] : '';
} else if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $json = file_get_contents('php://input');
    $data = json_decode($json, true);
    $caseId = isset($data['id']) ? $data['id'] : '';
}

if (empty($caseId)) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => '缺少案例ID']);
    exit;
}

// 清理案例ID
$caseId = preg_replace('/[^a-zA-Z0-9_-]/', '', $caseId);

// 数据文件路径
$dataFile = __DIR__ . '/../../data/cases/' . $caseId . '.json';

// 删除案例文件
if (file_exists($dataFile)) {
    if (!unlink($dataFile)) {
        http_response_code(500);
        echo json_encode(['success' => false, 'message' => '删除案例文件失败']);
        exit;
    }
}

// 从索引中移除
$indexFile = __DIR__ . '/../../data/cases-index.json';
if (file_exists($indexFile)) {
    $json = file_get_contents($indexFile);
    $index = json_decode($json, true);
    if (is_array($index)) {
        $index = array_filter($index, function($item) use ($caseId) {
            return $item['id'] !== $caseId;
        });
        $index = array_values($index); // 重新索引
        file_put_contents($indexFile, json_encode($index, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
    }
}

echo json_encode([
    'success' => true,
    'message' => '案例删除成功',
    'id' => $caseId
]);
