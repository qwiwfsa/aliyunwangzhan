<?php
/**
 * 页脚配置检查脚本 - 通过Web访问
 * 访问 http://localhost/hongdu/_check_footer.php
 */
require_once __DIR__ . '/config/db.php';

echo "<!DOCTYPE html><html><head><meta charset='utf-8'></head><body>";

try {
    $conn = getDB();
    echo "<h2>✅ 数据库连接成功</h2>";
    
    // 检查表
    $tables = $conn->query("SHOW TABLES LIKE 'footer_settings'");
    if ($tables->num_rows > 0) {
        echo "<p>✅ footer_settings 表存在</p>";
    } else {
        echo "<p style='color:red'>❌ footer_settings 表不存在</p>";
        exit;
    }
    
    // 检查数据
    $result = $conn->query("SELECT * FROM footer_settings ORDER BY FIELD(group_key,'brand','quick_links','service_links','contact','bottom'), sort_order ASC");
    echo "<h3>所有记录 (" . $result->num_rows . " 条):</h3>";
    echo "<table border='1' cellpadding='5' style='border-collapse:collapse'>";
    echo "<tr><th>id</th><th>group_key</th><th>item_key</th><th>item_label</th><th>item_value</th><th>item_url</th><th>sort_order</th></tr>";
    while ($row = $result->fetch_assoc()) {
        echo "<tr>";
        echo "<td>" . htmlspecialchars($row['id']) . "</td>";
        echo "<td>" . htmlspecialchars($row['group_key']) . "</td>";
        echo "<td>" . htmlspecialchars($row['item_key']) . "</td>";
        echo "<td>" . htmlspecialchars($row['item_label']) . "</td>";
        echo "<td>" . htmlspecialchars(mb_substr($row['item_value']??'', 0, 50)) . "</td>";
        echo "<td>" . htmlspecialchars($row['item_url']??'') . "</td>";
        echo "<td>" . htmlspecialchars($row['sort_order']) . "</td>";
        echo "</tr>";
    }
    echo "</table>";
    
    // 检查includes/footer.php
    echo "<h3>测试 footer.php 渲染:</h3>";
    echo "<div style='border:2px solid #ccc; padding:20px;'>";
    include __DIR__ . '/includes/footer.php';
    echo "</div>";
    
    $conn->close();
} catch (Exception $e) {
    echo "<h2 style='color:red'>❌ 错误: " . htmlspecialchars($e->getMessage()) . "</h2>";
}

echo "</body></html>";
