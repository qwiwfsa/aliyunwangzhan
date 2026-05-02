<?php
/**
 * 修复页脚数据：link_1 item_value 和 item_url
 * - link_1: item_value='首页好6' -> '首页', item_url='' -> '/'
 * 
 * 通过浏览器访问：http://localhost/hongdu/_fix_footer_data2.php
 */
header('Content-Type: text/html; charset=utf-8');
require_once __DIR__ . '/config/db.php';

echo "<!DOCTYPE html><html><head><meta charset='utf-8'><title>修复页脚数据</title></head><body style='font-family: sans-serif; padding: 20px;'>";
echo "<h1>🔧 修复页脚数据</h1><hr>";

try {
    $conn = getDB();
    $conn->set_charset('utf8mb4');
    
    $changes = 0;
    
    // ========== 1. 修复 link_1 ==========
    $stmt = $conn->prepare("SELECT id, item_value, item_url FROM footer_settings WHERE group_key='quick_links' AND item_key='link_1'");
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    
    if ($row) {
        $changes_msg = '';
        
        // 修复 item_value
        if ($row['item_value'] !== '首页') {
            $old_val = $row['item_value'];
            $upd = $conn->prepare("UPDATE footer_settings SET item_value=? WHERE id=?");
            $upd->bind_param('si', $val, $row['id']);
            $val = '首页';
            $upd->execute();
            $changes_msg .= "item_value: '{$old_val}' → '首页'<br>";
            $changes++;
        }
        
        // 修复 item_url
        if ($row['item_url'] !== '/') {
            $old_url = $row['item_url'] ?: '(空)';
            $upd = $conn->prepare("UPDATE footer_settings SET item_url=? WHERE id=?");
            $upd->bind_param('si', $url, $row['id']);
            $url = '/';
            $upd->execute();
            $changes_msg .= "item_url: '{$old_url}' → '/'<br>";
            $changes++;
        }
        
        if ($changes_msg) {
            echo "<p style='color:green'>✅ <b>link_1 已修复:</b><br>{$changes_msg}</p>";
        } else {
            echo "<p>✅ link_1 数据已正确，无需修改</p>";
        }
    } else {
        echo "<p style='color:orange'>⚠️ link_1 记录不存在</p>";
    }
    
    // ========== 2. 检查所有记录 ==========
    echo "<hr><h2>📋 全量数据检查</h2>";
    echo "<table border='1' cellpadding='6' style='border-collapse:collapse'>";
    echo "<tr><th>id</th><th>group</th><th>key</th><th>item_label</th><th>item_value</th><th>item_url</th><th>状态</th></tr>";
    
    $result = $conn->query("SELECT * FROM footer_settings ORDER BY FIELD(group_key,'brand','quick_links','service_links','contact','bottom'), sort_order ASC");
    $all_ok = true;
    $fixes_needed = [];
    
    while ($row = $result->fetch_assoc()) {
        $status = '✅ 正常';
        $row_issues = [];
        
        // 检查：属于 link 类型的记录，item_value 应与 item_label 一致
        // (contact 和 bottom 组的 item_value 可以自由设置，不与 label 挂钩)
        $is_link_type = in_array($row['group_key'], ['quick_links', 'service_links']);
        
        if ($is_link_type && $row['item_value'] !== $row['item_label']) {
            $row_issues[] = "item_value('{$row['item_value']}') ≠ item_label('{$row['item_label']}')";
        }
        
        // 检查：链接类型的记录，item_url 不能为空
        if ($is_link_type && empty($row['item_url'])) {
            $row_issues[] = "item_url 为空";
        }
        
        if ($row_issues) {
            $status = '❌ ' . implode('; ', $row_issues);
            $all_ok = false;
            $fixes_needed[] = $row;
        }
        
        echo "<tr style='background:" . (strpos($status, '❌') !== false ? '#ffe0e0' : '#e0ffe0') . "'>";
        echo "<td>" . htmlspecialchars($row['id']) . "</td>";
        echo "<td>" . htmlspecialchars($row['group_key']) . "</td>";
        echo "<td>" . htmlspecialchars($row['item_key']) . "</td>";
        echo "<td>" . htmlspecialchars($row['item_label']) . "</td>";
        echo "<td>" . htmlspecialchars($row['item_value'] ?? '') . "</td>";
        echo "<td>" . htmlspecialchars($row['item_url'] ?? '') . "</td>";
        echo "<td>" . $status . "</td>";
        echo "</tr>";
    }
    echo "</table>";
    
    if ($all_ok) {
        echo "<p style='color:green;font-size:18px;'>✅ 所有记录均已正确！</p>";
    } else {
        echo "<p style='color:orange;font-size:16px;'>⚠️ 尚有 " . count($fixes_needed) . " 条记录需要手动修正（暂不自动修改，等待确认）</p>";
        echo "<ul>";
        foreach ($fixes_needed as $fix) {
            echo "<li>id={$fix['id']} [{$fix['group_key']}.{$fix['item_key']}]: item_label='{$fix['item_label']}', item_value='{$fix['item_value']}'</li>";
        }
        echo "</ul>";
    }
    
    $conn->close();
    echo "<hr><p>✅ 修复完成，共执行 {$changes} 处修改</p>";
    
} catch (Exception $e) {
    echo "<h2 style='color:red'>❌ 错误: " . htmlspecialchars($e->getMessage()) . "</h2>";
}

echo "</body></html>";
