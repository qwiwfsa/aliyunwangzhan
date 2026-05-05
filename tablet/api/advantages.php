<?php
require_once __DIR__ . '/config.php';
setApiHeaders();
handlePreflight();
requireMethod('GET');

$pageIndex = readDataFile('page-index.json');
$cmsContent = readDataFile('../cms/content.json');

$data = [
    'advantages' => [
        ['id' => 1, 'icon' => 'fa-users', 'title' => '专业团队', 'description' => '拥有20年以上资金业务经验的专业团队，深谙金融市场运作规则，为企业提供精准的资金解决方案。', 'highlights' => ['资深金融专家', '专业风控体系', '定制化方案']],
        ['id' => 2, 'icon' => 'fa-bolt', 'title' => '高效服务', 'description' => '资金到位速度快，流程简化高效，最快1个工作日内即可完成资金调配，满足企业紧急资金需求。', 'highlights' => ['极速到账', '流程简化', '快速响应']],
        ['id' => 3, 'icon' => 'fa-shield-alt', 'title' => '资金安全', 'description' => '严格的资金风控体系和合规流程，确保每一笔交易的安全性和合法性，保护客户资金安全。', 'highlights' => ['合规经营', '资金托管', '风险可控']],
        ['id' => 4, 'icon' => 'fa-handshake', 'title' => '资源丰富', 'description' => '拥有超过100家银行及金融机构合作资源，资金渠道广泛，能够满足不同规模、不同场景的资金需求。', 'highlights' => ['银行合作', '资金渠道广', '规模灵活']],
        ['id' => 5, 'icon' => 'fa-star', 'title' => '客户至上', 'description' => '以客户需求为中心，提供一对一的专属服务，全程跟踪，确保客户体验和服务质量。', 'highlights' => ['专属顾问', '全程服务', '品质保障']],
        ['id' => 6, 'icon' => 'fa-chart-bar', 'title' => '行业深耕', 'description' => '深耕资金业务领域二十余年，深入了解各行业资金需求特点，积累了丰富的行业经验和成功案例。', 'highlights' => ['二十余年经验', '行业洞察', '案例丰富']]
    ],
    'stats' => [],
    'settings' => null
];

// 从page-index.json读取section和stats
if ($pageIndex) {
    if (isset($pageIndex['advantages'])) {
        $data['section'] = $pageIndex['advantages'];
    }
    if (isset($pageIndex['stats'])) {
        $statsItems = [];
        foreach ($pageIndex['stats'] as $key => $stat) {
            if (is_array($stat) && isset($stat['number'])) {
                $statsItems[] = ['label' => $stat['label'] ?? '', 'value' => $stat['number'] ?? '0'];
            }
        }
        $data['stats'] = $statsItems;
    }
}

// 从cms/content.json读取settings
if ($cmsContent && isset($cmsContent['settings'])) {
    $data['settings'] = $cmsContent['settings'];
}

jsonSuccess($data);
