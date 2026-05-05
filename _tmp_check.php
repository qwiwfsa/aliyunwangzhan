<?php
require_once __DIR__ . '/config/db.php';
$db = getDB();
$r = $db->query('SELECT * FROM faq_categories ORDER BY sort_order');
echo "faq_categories表内容:\n";
while ($row = $r->fetch_assoc()) {
    echo $row['sort_order'] . ' | ' . $row['cat_key'] . ' | ' . $row['cat_label'] . "\n";
}
// 也检查faq数据表的category字段
$r2 = $db->query('SELECT DISTINCT category FROM faq');
echo "\nfaq表中的分类:\n";
while ($row = $r2->fetch_assoc()) {
    echo $row['category'] . "\n";
}
