<?php
require_once 'admin/api/config.php';
$conn = getDbConnection();
$result = $conn->query('SELECT id, title, cover_image, status, category_id, created_at FROM cms_articles ORDER BY id DESC LIMIT 20');
echo "=== 文章列表 ===\n";
while ($row = $result->fetch_assoc()) {
    echo 'ID=' . $row['id'] . ' | title=' . $row['title'] . ' | cover=' . ($row['cover_image'] ?: '(empty)') . ' | status=' . $row['status'] . ' | cat=' . $row['category_id'] . ' | created=' . $row['created_at'] . "\n";
}
echo "\n=== 统计 ===\n";
echo 'Total: ' . $conn->query('SELECT COUNT(*) as t FROM cms_articles')->fetch_assoc()['t'] . "\n";
$pub = $conn->query("SELECT COUNT(*) as t FROM cms_articles WHERE status='published'")->fetch_assoc()['t'];
echo 'Published: ' . $pub . "\n";
$draft = $conn->query("SELECT COUNT(*) as t FROM cms_articles WHERE status='draft'")->fetch_assoc()['t'];
echo 'Draft: ' . $draft . "\n";

// Check config.php for DB connection
echo "\n=== config.php structure ===\n";
echo file_get_contents('admin/api/config.php');
$conn->close();
?>
