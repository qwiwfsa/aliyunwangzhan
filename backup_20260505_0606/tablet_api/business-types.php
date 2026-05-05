<?php
/**
 * 平板端 - 案例业务类型API
 * 返回所有可用的业务类型，与桌面端共享数据
 */

// 设置响应头
header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

// 禁用缓存
header('Cache-Control: no-cache, no-store, must-revalidate');
header('Pragma: no-cache');
header('Expires: 0');

// 处理预检请求
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

// 只允许GET请求
if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => '方法不允许']);
    exit;
}

// 获取所有已发布案例中出现的业务类型（与桌面端相同的数据源）
$dataDir = __DIR__ . '/../../data/cases/';

$cases = [];

// 从案例文件读取
if (is_dir($dataDir)) {
    $files = glob($dataDir . '*.json');
    foreach ($files as $file) {
        $json = file_get_contents($file);
        $caseData = json_decode($json, true);
        if ($caseData) {
            $cases[] = $caseData;
        }
    }
}

// 收集所有出现的业务类型
$typeNames = [];
$typeOrder = ['过桥', '摆账', '亮资', '冲量', '定增', '应收账款'];

foreach ($cases as $case) {
    if (isset($case['type']) && !empty($case['type'])) {
        $typeNames[$case['type']] = true;
    }
}

// 按预设顺序排列，不在预设列表中的放在后面
$sortedTypes = [];
foreach ($typeOrder as $type) {
    if (isset($typeNames[$type])) {
        $sortedTypes[] = ['name' => $type];
        unset($typeNames[$type]);
    }
}
foreach (array_keys($typeNames) as $type) {
    $sortedTypes[] = ['name' => $type];
}

// 如果没有任何案例数据，返回默认的业务类型
if (empty($sortedTypes)) {
    $sortedTypes = [
        ['name' => '过桥'],
        ['name' => '摆账'],
        ['name' => '亮资'],
        ['name' => '冲量'],
        ['name' => '定增'],
        ['name' => '应收账款']
    ];
}

echo json_encode([
    'success' => true,
    'data' => $sortedTypes,
    'total' => count($sortedTypes)
]);
