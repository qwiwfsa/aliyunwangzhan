<?php
$conn = @new mysqli('localhost', 'root', '', 'hongdu', '3306');
$conn->set_charset('utf8mb4');
$r = $conn->query("SELECT id, group_key, item_key, item_label, item_value FROM footer_settings WHERE group_key IN ('quick_links','service_links') ORDER BY group_key, sort_order");
while ($row = $r->fetch_assoc()) {
    $empty_val = empty($row['item_value']) ? 'EMPTY' : 'OK';
    echo "id={$row['id']} group={$row['group_key']} key={$row['item_key']} label='{$row['item_label']}' value='{$row['item_value']}' [{$empty_val}]\n";
}
