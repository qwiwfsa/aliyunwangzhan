<?php
require_once __DIR__ . '/config.php';
setApiHeaders();
handlePreflight();
requireMethod('GET');

$pageIndex = readDataFile('page-index.json');
$cmsContent = readDataFile('../cms/content.json');

$data = [
    'hero' => [],
    'services_section' => ['title' => '核心业务领域', 'subtitle' => '', 'cards' => []],
    'stats' => [],
    'services' => [],
    'contact' => null,
    'settings' => null
];

// 从page-index.json读取数据
if ($pageIndex) {
    if (isset($pageIndex['hero'])) {
        $data['hero'] = $pageIndex['hero'];
    }
    if (isset($pageIndex['stats'])) {
        $statsItems = [];
        foreach ($pageIndex['stats'] as $key => $stat) {
            if (is_array($stat) && isset($stat['number'])) {
                $statsItems[] = $stat;
            }
        }
        $data['stats'] = $statsItems;
    }
    if (isset($pageIndex['services'])) {
        $svc = $pageIndex['services'];
        $servicesCards = [];
        for ($i = 1; $i <= 4; $i++) {
            if (isset($svc['card' . $i])) {
                $servicesCards[] = $svc['card' . $i];
            }
        }
        $data['services_section'] = [
            'title' => $svc['title'] ?? '核心业务领域',
            'subtitle' => $svc['subtitle'] ?? '',
            'cards' => $servicesCards
        ];
    }
}

// 从cms/content.json覆盖数据
if ($cmsContent) {
    if (isset($cmsContent['pages']['index']['sections']['hero'])) {
        $data['hero'] = $cmsContent['pages']['index']['sections']['hero'];
    }
    if (isset($cmsContent['pages']['index']['sections']['services'])) {
        $data['services_section'] = $cmsContent['pages']['index']['sections']['services'];
    }
    if (isset($cmsContent['settings'])) {
        $data['settings'] = $cmsContent['settings'];
    }
    if (isset($cmsContent['settings']['contact'])) {
        $data['contact'] = $cmsContent['settings']['contact'];
    }
}

// 确保services_section包含items（兼容前端需要）
if (!isset($data['services_section']['items'])) {
    $data['services_section']['items'] = $data['services_section']['cards'] ?? [];
}

jsonSuccess($data);
