<?php
require_once __DIR__ . '/config.php';
setApiHeaders();
handlePreflight();
requireMethod('GET');

$pageIndex = readDataFile('page-index.json');
$cmsContent = readDataFile('../cms/content.json');

$data = [
    'services' => [],
    'categories' => [],
    'nav_services' => [
        ['id' => 1, 'name' => '上市公司过桥', 'icon' => 'fa-chart-line', 'desc' => '短拆、股票解质押过桥、募集账户归还过桥、产业基金备案过桥'],
        ['id' => 2, 'name' => '企业摆账', 'icon' => 'fa-hand-holding-usd', 'desc' => '长短期定存摆账、云信票据实摆、过账实趴、抵押类资金过桥'],
        ['id' => 3, 'name' => '银行存款冲量', 'icon' => 'fa-university', 'desc' => '时点冲量、日均业务、月末冲量、一年期/三年期定期存款'],
        ['id' => 4, 'name' => '应收账款融资', 'icon' => 'fa-file-invoice-dollar', 'desc' => '置换云信票据、可拆分流转支付、融资贴现、准入宽松']
    ]
];

// 从page-index.json读取section数据
if ($pageIndex && isset($pageIndex['services'])) {
    $data['section'] = [
        'title' => $pageIndex['services']['title'] ?? '核心业务领域',
        'subtitle' => $pageIndex['services']['subtitle'] ?? ''
    ];
}

// 从cms/content.json读取数据
if ($cmsContent && isset($cmsContent['pages']['services'])) {
    $data['page_title'] = $cmsContent['pages']['services']['title'] ?? null;
    $data['page_description'] = $cmsContent['pages']['services']['description'] ?? null;
}

// 从cms/content.json获取services items
if ($cmsContent && isset($cmsContent['pages']['index']['sections']['services']['items'])) {
    $data['services'] = $cmsContent['pages']['index']['sections']['services']['items'];
}

// 尝试从数据库读取categories
$db = getDB();
if ($db) {
    $tableCheck = $db->query("SHOW TABLES LIKE 'categories'");
    if ($tableCheck && $tableCheck->num_rows > 0) {
        $catResult = $db->query("SELECT * FROM categories WHERE type = 'service' OR type = 'business' ORDER BY sort_order, id");
        if ($catResult) {
            $cats = [];
            while ($cat = $catResult->fetch_assoc()) {
                $cats[] = $cat;
            }
            if (!empty($cats)) {
                $data['categories'] = $cats;
            }
        }
    }
}

jsonSuccess($data);
