<?php
/**
 * 平板端 - 案例业务类型API
 * 返回所有可用的业务类型，与api/business-types.php共享数据源
 */

header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');
header('Cache-Control: no-cache, no-store, must-revalidate');
header('Pragma: no-cache');
header('Expires: 0');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(204);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => '方法不允许']);
    exit;
}

// 从主站的data/cases目录读取案例，提取所有业务类型
$dataDir = dirname(__DIR__, 2) . '/data/cases/';

// 先从数据库读取（使用已有的config）
require_once __DIR__ . '/../config.php';

$typesFromDB = [];
$db = getDB();
if ($db) {
    try {
        $stmt = $db->query("SELECT DISTINCT category FROM cases WHERE status = 1 AND category IS NOT NULL AND category != '' ORDER BY sort_order ASC, category ASC");
        if ($stmt) {
            $rows = $stmt->fetchAll(PDO::FETCH_COLUMN);
            foreach ($rows as $cat) {
                $typesFromDB[] = $cat;
            }
        }
    } catch (PDOException $e) {
        // 降级到文件读取
    }
}

// 从案例JSON文件读取
$typeNames = [];
$typeOrder = ['企业过桥', '企业摆账', '工程亮资', '银行冲量存款', '云信融资出表'];

// 先收集DB中的类型
foreach ($typesFromDB as $t) {
    if (!empty($t)) {
        $typeNames[$t] = true;
    }
}

// 再从cases目录读取
if (is_dir($dataDir)) {
    $files = glob($dataDir . '*.json');
    if ($files === false) { $files = []; }
    foreach ($files as $file) {
        $json = file_get_contents($file);
        $caseData = json_decode($json, true);
        if ($caseData && isset($caseData['type']) && !empty($caseData['type'])) {
            $typeNames[$caseData['type']] = true;
        }
    }
}

// 排序
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

// 默认
if (empty($sortedTypes)) {
    $sortedTypes = [
        ['name' => '企业过桥'],
        ['name' => '企业摆账'],
        ['name' => '工程亮资'],
        ['name' => '银行冲量存款'],
        ['name' => '云信融资出表']
    ];
}

echo json_encode([
    'success' => true,
    'data' => $sortedTypes,
    'total' => count($sortedTypes)
], JSON_UNESCAPED_UNICODE);
