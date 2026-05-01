<?php
/**
 * 前端案例列表API
 * 供前端页面读取已发布的案例数据
 */

// 开启错误报告（开发环境）
error_reporting(E_ALL);
ini_set('display_errors', 0);

// 设置响应头
header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');
header('Cache-Control: no-cache, no-store, must-revalidate');
header('Pragma: no-cache');
header('Expires: 0');

// 错误处理
function handleError($errno, $errstr, $errfile, $errline) {
    error_log("[cases.php] Error [$errno]: $errstr in $errfile on line $errline");
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => '服务器内部错误']);
    exit;
}
set_error_handler('handleError');

try {

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

// 数据目录 - 根目录下的data文件夹
$dataDir = __DIR__ . '/../data/cases/';
$indexFile = __DIR__ . '/../data/cases-index.json';

$cases = [];

// 优先从索引文件读取
if (file_exists($indexFile)) {
    $json = file_get_contents($indexFile);
    $cases = json_decode($json, true);
    if (!is_array($cases)) {
        $cases = [];
    }
    // 确保每个案例都有status字段
    $casesWithStatus = [];
    foreach ($cases as $case) {
        if (!isset($case['status'])) {
            $case['status'] = 'draft';
        }
        $casesWithStatus[] = $case;
    }
    $cases = $casesWithStatus;
    
    // 验证索引中的案例是否实际存在（防止索引与文件不同步）
    if (file_exists($dataDir)) {
        $validCases = [];
        foreach ($cases as $case) {
            // 检查案例文件是否存在
            $caseFile = $dataDir . $case['id'] . '.json';
            if (file_exists($caseFile)) {
                $validCases[] = $case;
            } else {
                error_log("[cases.php] 案例文件不存在，从索引中跳过: " . $case['id']);
            }
        }
        $cases = $validCases;
    }
} else {
    // 从单个案例文件读取
    if (file_exists($dataDir)) {
        $files = glob($dataDir . '*.json');
        foreach ($files as $file) {
            $json = file_get_contents($file);
            $caseData = json_decode($json, true);
            // 确保status字段存在，默认为draft
            $status = isset($caseData['status']) ? $caseData['status'] : 'draft';
            if ($caseData && $status === 'published') {
                $cases[] = [
                    'id' => $caseData['id'],
                    'title' => $caseData['title'],
                    'type' => $caseData['type'],
                    'city' => $caseData['city'],
                    'amount' => $caseData['amount'],
                    'image' => $caseData['image'],
                    'coverImage' => isset($caseData['coverImage']) ? $caseData['coverImage'] : $caseData['image'],
                    'images' => isset($caseData['images']) ? $caseData['images'] : [],
                    'summary' => isset($caseData['summary']) ? $caseData['summary'] : '',
                    'status' => $status,
                    'lastModified' => $caseData['lastModified']
                ];
            }
        }
    }
}

// 只返回已发布的案例
$publishedCases = array_filter($cases, function($case) {
    return isset($case['status']) && $case['status'] === 'published';
});

// 按ID去重，防止重复案例显示
$uniqueCases = [];
$seenIds = [];
foreach ($publishedCases as $case) {
    $caseId = isset($case['id']) ? $case['id'] : '';
    if ($caseId && !in_array($caseId, $seenIds)) {
        $seenIds[] = $caseId;
        $uniqueCases[] = $case;
    }
}
$publishedCases = $uniqueCases;

// 按最后修改时间排序
usort($publishedCases, function($a, $b) {
    $timeA = isset($a['lastModified']) ? strtotime($a['lastModified']) : 0;
    $timeB = isset($b['lastModified']) ? strtotime($b['lastModified']) : 0;
    return $timeB - $timeA;
});

// 重新索引数组（去重后已经重新索引过了，这里保持兼容）
$publishedCases = array_values($publishedCases);

echo json_encode([
    'success' => true,
    'cases' => $publishedCases,
    'total' => count($publishedCases)
]);

} catch (Exception $e) {
    error_log('[cases.php] Exception: ' . $e->getMessage());
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => '服务器错误: ' . $e->getMessage()]);
    exit;
}
