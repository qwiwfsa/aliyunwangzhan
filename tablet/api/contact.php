<?php
require_once __DIR__ . '/config.php';
setApiHeaders();
handlePreflight();
requireMethod('GET');

$cmsContent = readDataFile('../cms/content.json');

$data = [
    'contact' => [
        'phone' => '13552883008',
        'name' => '王立力',
        'email' => 'wanglizhongguo@126.com'
    ],
    'settings' => [],
    'contacts_list' => [],
    'db_settings' => []
];

// 从cms/content.json读取数据
if ($cmsContent) {
    if (isset($cmsContent['settings'])) {
        $data['settings'] = $cmsContent['settings'];
    }
    if (isset($cmsContent['settings']['contact'])) {
        $contact = $cmsContent['settings']['contact'];
        $data['contact'] = [
            'phone' => $contact['phone'] ?? '13552883008',
            'name' => $contact['name'] ?? '王立力',
            'email' => $contact['email'] ?? 'wanglizhongguo@126.com'
        ];
    }
    if (isset($cmsContent['pages']['contact'])) {
        $data['page_title'] = $cmsContent['pages']['contact']['title'] ?? null;
        $data['page_description'] = $cmsContent['pages']['contact']['description'] ?? null;
    }
}

// 尝试从数据库读取settings
$db = getDB();
if ($db) {
    $tableCheck = $db->query("SHOW TABLES LIKE 'settings'");
    if ($tableCheck && $tableCheck->num_rows > 0) {
        $settingsResult = $db->query("SELECT `key`, `value` FROM settings WHERE `key` IN ('contact_phone', 'contact_name', 'contact_email', 'site_name', 'site_logo')");
        if ($settingsResult) {
            while ($s = $settingsResult->fetch_assoc()) {
                $data['db_settings'][$s['key']] = $s['value'];
            }
        }
    }
}

// 构建联系人列表
$data['contacts_list'] = [
    ['type' => 'phone', 'icon' => 'fa-phone', 'label' => '联系电话', 'value' => $data['contact']['phone'], 'action' => 'tel:' . $data['contact']['phone']],
    ['type' => 'wechat', 'icon' => 'fa-weixin', 'label' => '微信', 'value' => $data['contact']['name'], 'action' => ''],
    ['type' => 'email', 'icon' => 'fa-envelope', 'label' => '电子邮箱', 'value' => $data['contact']['email'], 'action' => 'mailto:' . $data['contact']['email']]
];

jsonSuccess($data);
