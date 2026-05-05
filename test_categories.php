<?php
// 测试分类API
header('Content-Type: text/plain; charset=utf-8');

// 直接连接数据库
$host = 'localhost';
$user = 'root';
$pass = '';
$dbname = 'hongdu';

$conn = new mysqli($host, $user, $pass, $dbname);
if ($conn->connect_error) {
    die("数据库连接失败: " . $conn->connect_error);
}
$conn->set_charset('utf8mb4');

$result = $conn->query("SELECT id, name, description, sort_order FROM cms_categories ORDER BY sort_order ASC, id ASC");
$categories = [];
while ($row = $result->fetch_assoc()) {
    $categories[] = $row;
}

echo "数据库中的分类（共" . count($categories) . "个）：\n";
echo "========================================\n";
foreach($categories as $cat) {
    echo "ID: {$cat['id']} | 名称: {$cat['name']} | 排序: {$cat['sort_order']}\n";
}

$conn->close();
