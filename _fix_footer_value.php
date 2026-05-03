<?php
/**
 * 修复页脚导航数据：将 item_label 复制到 item_value（当 item_value 为空时）
 * 快速链接、业务链接的显示名称存在 item_label 中，但前端使用 item_value
 * 
 * 通过浏览器访问: http://localhost/hongdu/_fix_footer_value.php
 */
$conn = @new mysqli('localhost', 'root', '', 'hongdu', '3306');
$conn->set_charset('utf8mb4');
if ($conn->connect_error) die("连接失败: " . $conn->connect_error);

echo "<html><meta charset='utf-8'><body style='font-family: sans-serif; padding: 20px;'>";
echo "<h1>🔧 修复页脚导航数据</h1>";

// 1. 修复 link 类型数据：item_value 为空时从 item_label 复制
$result = $conn->query("SELECT id, group_key, item_key, item_label, item_value FROM footer_settings WHERE group_key IN ('quick_links','service_links') AND (item_value IS NULL OR item_value = '')");
$count = 0;
while ($row = $result->fetch_assoc()) {
    $upd = $conn->prepare("UPDATE footer_settings SET item_value=? WHERE id=?");
    $val = $row['item_label'];
    $upd->bind_param('si', $val, $row['id']);
    $upd->execute();
    echo "✅ [{$row['group_key']}.{$row['item_key']}] item_value='{$row['item_label']}'<br>";
    $count++;
}
echo "<p style='color:green'>✅ 共修复 {$count} 条链接数据的 item_value</p>";

// 2. 确保 item_url 有值（没有就默认用'#'）
$result2 = $conn->query("SELECT id, group_key, item_key, item_url FROM footer_settings WHERE group_key IN ('quick_links','service_links') AND (item_url IS NULL OR item_url = '')");
$count2 = 0;
while ($row = $result2->fetch_assoc()) {
    $upd2 = $conn->prepare("UPDATE footer_settings SET item_url='#' WHERE id=?");
    $upd2->bind_param('i', $row['id']);
    $upd2->execute();
    echo "🔗 [{$row['group_key']}.{$row['item_key']}] item_url='#'<br>";
    $count2++;
}
echo "<p style='color:green'>✅ 共修复 {$count2} 条链接数据的 item_url</p>";

// 3. 显示修复后的数据
echo "<hr><h2>📋 修复后数据</h2>";
echo "<table border='1' cellpadding='6' style='border-collapse:collapse'>";
echo "<tr><th>id</th><th>group</th><th>key</th><th>item_label</th><th>item_value</th><th>item_url</th></tr>";
$all = $conn->query("SELECT * FROM footer_settings WHERE group_key IN ('quick_links','service_links') ORDER BY group_key, sort_order");
while ($r = $all->fetch_assoc()) {
    echo "<tr><td>{$r['id']}</td><td>{$r['group_key']}</td><td>{$r['item_key']}</td><td>{$r['item_label']}</td><td>{$r['item_value']}</td><td>{$r['item_url']}</td></tr>";
}
echo "</table>";

$conn->close();
echo "<hr><p>✅ 修复完成！请刷新页脚管理页面查看效果。</p>";
echo "</body></html>";
