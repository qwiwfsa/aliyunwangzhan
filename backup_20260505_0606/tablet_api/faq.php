<?php
require_once __DIR__ . '/config.php';
setApiHeaders();
handlePreflight();
requireMethod('GET');

$cmsContent = readDataFile('../cms/content.json');

$data = [
    'faq' => [],
    'categories' => [],
    'total' => 0
];

// 从cms/content.json读取
if ($cmsContent && isset($cmsContent['pages']['faq'])) {
    $data['page_title'] = $cmsContent['pages']['faq']['title'] ?? null;
}

$faqList = [];
$categoryMap = [];

// 先从数据库读取
$db = getDB();
if ($db) {
    $stmt = $db->query("SHOW TABLES LIKE 'faq'");
    if ($stmt && $stmt->rowCount() > 0) {
        $faqStmt = $db->query("SELECT * FROM faq WHERE status = 1 ORDER BY sort_order ASC, id ASC");
        if ($faqStmt) {
            $dbFaq = $faqStmt->fetchAll();
            if (!empty($dbFaq)) {
                foreach ($dbFaq as $f) {
                    $cat = $f['category'] ?? '其他';
                    $item = [
                        'id' => $f['id'],
                        'question' => $f['question'] ?? $f['title'] ?? '',
                        'answer' => $f['answer'] ?? $f['content'] ?? '',
                        'category' => $cat,
                        'sort_order' => $f['sort_order'] ?? 0,
                        'status' => 'published'
                    ];
                    $faqList[] = $item;
                    if (!isset($categoryMap[$cat])) {
                        $categoryMap[$cat] = [];
                    }
                    $categoryMap[$cat][] = $item;
                }
            }
        }
    }
}

// 数据库没有数据，从JSON文件读取
if (empty($faqList)) {
    $faqDir = DATA_DIR . '/faq';
    if (is_dir($faqDir)) {
        $files = glob($faqDir . '/*.json');
        if ($files === false) { $files = []; }
        foreach ($files as $file) {
            $item = json_decode(file_get_contents($file), true);
            if ($item) {
                $faqList[] = $item;
                $cat = $item['category'] ?? '其他';
                if (!isset($categoryMap[$cat])) {
                    $categoryMap[$cat] = [];
                }
                $categoryMap[$cat][] = $item;
            }
        }
    }
}

// 分类排序
if (!empty($categoryMap)) {
    foreach ($categoryMap as $cat => &$items) {
        usort($items, function($a, $b) {
            $orderA = $a['sort_order'] ?? 0;
            $orderB = $b['sort_order'] ?? 0;
            return $orderA - $orderB;
        });
    }
    unset($items);
}

$data['faq'] = array_values($faqList);
$data['categories'] = $categoryMap;
$data['total'] = count($faqList);

jsonSuccess($data);
