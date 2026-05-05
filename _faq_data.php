<?php
require_once __DIR__ . '/admin/api/faq-data.php';
// 获取FAQ分类数据
$faqApi = file_get_contents('http://localhost/hongdu/admin/api/faq-data.php');
$faqData = json_decode($faqApi, true);
$categoriesOrder = isset($faqData['categories_order']) ? $faqData['categories_order'] : [];
// 防止api调用失败
if (empty($categoriesOrder)) {
    $categoriesOrder = [
        ['key' => 'baizhang', 'label' => '摆账业务', 'sort_order' => 0],
        ['key' => 'receivable', 'label' => '云信融资出表', 'sort_order' => 1],
        ['key' => 'liangzi', 'label' => '亮资业务', 'sort_order' => 2],
        ['key' => 'guoqiao', 'label' => '过桥资金', 'sort_order' => 3],
        ['key' => 'deposit', 'label' => '银行存款', 'sort_order' => 4],
        ['key' => 'general', 'label' => '一般问题', 'sort_order' => 5],
    ];
}
$categoriesJson = json_encode($categoriesOrder, JSON_UNESCAPED_UNICODE);
$faqItemsJson = isset($faqData['data']) ? json_encode($faqData['data'], JSON_UNESCAPED_UNICODE) : 'null';
?>
