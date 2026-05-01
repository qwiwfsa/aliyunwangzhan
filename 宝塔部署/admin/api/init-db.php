<?php
/**
 * CMS数据库初始化脚本
 * 创建表结构并插入默认数据
 */

// 引入数据库配置
require_once __DIR__ . '/config.php';

echo "正在初始化CMS数据库...\n\n";

// 获取数据库连接
$conn = getDbConnection();

echo "✓ 数据库连接成功\n";

// 初始化数据库表结构
initDatabase($conn);

echo "✓ 数据库表结构已创建\n";

// 验证数据
$result = $conn->query("SELECT page_id, page_name, title FROM cms_pages");
echo "\n已创建的页面数据:\n";
echo str_repeat("-", 50) . "\n";
while ($row = $result->fetch_assoc()) {
    echo sprintf("%-15s %-15s %s\n", $row['page_id'], $row['page_name'], $row['title']);
}
echo str_repeat("-", 50) . "\n";

$conn->close();

echo "\n✓ 数据库初始化完成！\n";
