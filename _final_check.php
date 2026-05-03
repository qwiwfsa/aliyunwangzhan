<?php
$conn = @new mysqli('localhost', 'root', '', 'hongdu', '3306');
$conn->set_charset('utf8mb4');

echo "=== 快速链接数据 ===\n";
$r = $conn->query("SELECT * FROM footer_settings ORDER BY FIELD(group_key,'brand','quick_links','service_links','contact','bottom'), sort_order");
while ($row = $r->fetch_assoc()) {
    $g = $row['group_key'];
    $k = $row['item_key'];
    $l = $row['item_label'];
    $v = $row['item_value'];
    $u = $row['item_url'];
    $empty_val = ($v === '' || $v === null) ? ' [⚠️ EMPTY]' : '';
    $empty_url = ($u === '' || $u === null) ? ' [⚠️ NO URL]' : '';
    echo "{$g}|{$k} | label='{$l}' value='{$v}'{$empty_val} url='{$u}'{$empty_url}\n";
}
$conn->close();
