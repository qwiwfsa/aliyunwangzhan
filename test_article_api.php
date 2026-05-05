<?php
// 测试文章API
header('Content-Type: text/plain; charset=utf-8');

$host = 'localhost';
$user = 'root';
$pass = '';
$dbname = 'hongdu';

$conn = new mysqli($host, $user, $pass, $dbname);
if ($conn->connect_error) {
    die("数据库连接失败: " . $conn->connect_error);
}
$conn->set_charset('utf8mb4');

echo "数据库中的文章：\n";
echo "========================================\n";

$result = $conn->query("SELECT id, title, cover_image, status FROM cms_articles ORDER BY id DESC");
while ($row = $result->fetch_assoc()) {
    echo "ID: {$row['id']} | 标题: {$row['title']} | 状态: {$row['status']}\n";
    echo "封面: " . ($row['cover_image'] ? substr($row['cover_image'], 0, 50) . '...' : '无') . "\n\n";
}

$conn->close();
