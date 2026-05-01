<?php
/**
 * 案例详情保存API
 * 保存案例数据到JSON文件
 */

// 设置响应头
header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

// 处理预检请求
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

// 只允许POST请求
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => '方法不允许']);
    exit;
}

// 获取POST数据
$json = file_get_contents('php://input');
$data = json_decode($json, true);

if (!$data) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => '无效的JSON数据']);
    exit;
}

// 验证必需字段
if (empty($data['id'])) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => '缺少案例ID']);
    exit;
}

$caseId = preg_replace('/[^a-zA-Z0-9_-]/', '', $data['id']);

// 数据目录
$dataDir = __DIR__ . '/../../data/cases/';

// 确保目录存在
if (!file_exists($dataDir)) {
    mkdir($dataDir, 0755, true);
}

// 准备保存的数据
$caseData = [
    'id' => $caseId,
    'title' => isset($data['title']) ? $data['title'] : '',
    'type' => isset($data['type']) ? $data['type'] : '过桥',
    'city' => isset($data['city']) ? $data['city'] : '北京',
    'summary' => isset($data['summary']) ? $data['summary'] : '',
    'amount' => isset($data['amount']) ? $data['amount'] : '',
    'period' => isset($data['period']) ? $data['period'] : '',
    'year' => isset($data['year']) ? $data['year'] : date('Y'),
    'image' => isset($data['image']) ? $data['image'] : '',
    'images' => isset($data['images']) && is_array($data['images']) ? $data['images'] : [],
    'hasVideo' => isset($data['hasVideo']) ? (bool)$data['hasVideo'] : false,
    'video' => isset($data['video']) ? $data['video'] : '',
    'detail' => isset($data['detail']) ? $data['detail'] : '',
    'highlights' => isset($data['highlights']) && is_array($data['highlights']) ? $data['highlights'] : [],
    'status' => isset($data['status']) ? $data['status'] : 'draft',
    'lastModified' => date('Y-m-d H:i:s')
];

// 如果没有主图但有图片列表，使用第一张作为主图
if (empty($caseData['image']) && !empty($caseData['images'])) {
    $caseData['image'] = $caseData['images'][0];
}

// 保存到文件
$dataFile = $dataDir . $caseId . '.json';
$result = file_put_contents($dataFile, json_encode($caseData, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));

if ($result === false) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => '保存案例数据失败']);
    exit;
}

// 同时更新案例列表索引
updateCaseIndex($caseData);

echo json_encode([
    'success' => true,
    'message' => '案例保存成功',
    'case' => $caseData
]);

/**
 * 更新案例列表索引
 */
function updateCaseIndex($caseData) {
    $indexFile = __DIR__ . '/../../data/cases-index.json';
    
    $index = [];
    if (file_exists($indexFile)) {
        $json = file_get_contents($indexFile);
        $index = json_decode($json, true);
        if (!is_array($index)) {
            $index = [];
        }
    }
    
    // 查找是否已存在
    $found = false;
    foreach ($index as &$item) {
        if ($item['id'] === $caseData['id']) {
            $item['title'] = $caseData['title'];
            $item['type'] = $caseData['type'];
            $item['city'] = $caseData['city'];
            $item['amount'] = $caseData['amount'];
            $item['image'] = $caseData['image'];
            $item['status'] = $caseData['status'];
            $item['lastModified'] = $caseData['lastModified'];
            $found = true;
            break;
        }
    }
    
    // 如果不存在，添加新条目
    if (!$found) {
        $index[] = [
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
    
    file_put_contents($indexFile, json_encode($index, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
}
