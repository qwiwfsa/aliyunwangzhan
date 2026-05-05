<?php
require_once __DIR__ . '/config.php';
setApiHeaders();
handlePreflight();
requireMethod('GET');

$cmsContent = readDataFile('../cms/content.json');

$data = [
    'news' => [],
    'total' => 0,
    'categories' => []
];

// 从cms/content.json读取
if ($cmsContent && isset($cmsContent['pages']['news'])) {
    $data['page_title'] = $cmsContent['pages']['news']['title'] ?? null;
    $data['page_description'] = $cmsContent['pages']['news']['description'] ?? null;
}

$newsList = [];
$categoryMap = [];

// 先从数据库读取
$db = getDB();
if ($db) {
    $stmt = $db->query("SELECT * FROM news WHERE status = 1 ORDER BY created_at DESC");
    if ($stmt) {
        $dbNews = $stmt->fetchAll();
        if (!empty($dbNews)) {
            foreach ($dbNews as $n) {
                $cat = $n['category'] ?? '行业动态';
                $item = [
                    'id' => $n['id'],
                    'title' => $n['title'],
                    'summary' => $n['summary'] ?? '',
                    'content' => $n['content'] ?? '',
                    'category' => $cat,
                    'cover_image' => $n['cover_image'] ?? '',
                    'author' => $n['author'] ?? '鸿都资本',
                    'views' => $n['views'] ?? 0,
                    'date' => date('Y-m-d', strtotime($n['created_at'])),
                    'created_at' => $n['created_at'] ?? '',
                    'updated_at' => $n['updated_at'] ?? '',
                    'status' => 'published'
                ];
                $newsList[] = $item;
                $categoryMap[$cat] = true;
            }
        }
    }
}

// 数据库没有数据，从JSON文件读取
if (empty($newsList)) {
    $newsDir = DATA_DIR . '/news';
    if (is_dir($newsDir)) {
        $files = glob($newsDir . '/*.json');
        if ($files === false) { $files = []; }
        foreach ($files as $file) {
            $item = json_decode(file_get_contents($file), true);
            if ($item) {
                $newsList[] = $item;
                $cat = $item['category'] ?? '行业动态';
                $categoryMap[$cat] = true;
            }
        }
    }
    usort($newsList, function($a, $b) {
        $dateA = isset($a['date']) ? strtotime($a['date']) : 0;
        $dateB = isset($b['date']) ? strtotime($b['date']) : 0;
        return $dateB - $dateA;
    });
}

$data['categories'] = array_keys($categoryMap);
$data['news'] = array_values($newsList);
$data['total'] = count($newsList);

jsonSuccess($data);
