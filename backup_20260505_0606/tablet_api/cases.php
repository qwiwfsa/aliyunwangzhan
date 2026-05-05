<?php
require_once __DIR__ . '/config.php';
setApiHeaders();
handlePreflight();
requireMethod('GET');

$pageIndex = readDataFile('page-index.json');
$cmsContent = readDataFile('../cms/content.json');

$data = [
    'cases' => [],
    'total' => 0,
    'section' => null
];

// 从page-index.json读取section数据
if ($pageIndex && isset($pageIndex['cases'])) {
    $data['section'] = $pageIndex['cases'];
}

// 从cms/content.json读取数据
if ($cmsContent && isset($cmsContent['pages']['cases'])) {
    $data['page_title'] = $cmsContent['pages']['cases']['title'] ?? null;
    $data['page_description'] = $cmsContent['pages']['cases']['description'] ?? null;
}

$cases = [];

// 先从数据库读取
$db = getDB();
if ($db) {
    $stmt = $db->query("SELECT * FROM cases WHERE status = 1 ORDER BY sort_order ASC, created_at DESC");
    if ($stmt) {
        $dbCases = $stmt->fetchAll();
        if (!empty($dbCases)) {
            foreach ($dbCases as $c) {
                $cases[] = [
                    'id' => $c['id'],
                    'title' => $c['title'],
                    'company' => $c['company'] ?? '',
                    'amount' => $c['amount'] ?? '',
                    'period' => $c['period'] ?? '',
                    'category' => $c['category'] ?? '',
                    'description' => $c['description'] ?? '',
                    'content' => $c['content'] ?? '',
                    'image' => $c['image'] ?? '',
                    'status' => 'published',
                    'created_at' => $c['created_at'] ?? '',
                    'updated_at' => $c['updated_at'] ?? ''
                ];
            }
        }
    }
}

// 数据库没有数据，从JSON文件读取
if (empty($cases)) {
    $casesDir = DATA_DIR . '/cases';
    if (is_dir($casesDir)) {
        $files = glob($casesDir . '/*.json');
        if ($files === false) { $files = []; }
        foreach ($files as $file) {
            $caseData = json_decode(file_get_contents($file), true);
            if ($caseData && isset($caseData['status']) && $caseData['status'] === 'published') {
                $cases[] = $caseData;
            }
        }
    }
    usort($cases, function($a, $b) {
        $timeA = isset($a['lastModified']) ? strtotime($a['lastModified']) : 0;
        $timeB = isset($b['lastModified']) ? strtotime($b['lastModified']) : 0;
        return $timeB - $timeA;
    });
}

$data['cases'] = array_values($cases);
$data['total'] = count($cases);

jsonSuccess($data);
