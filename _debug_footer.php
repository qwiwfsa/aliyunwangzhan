<?php
// Force local XAMPP credentials
$conn = @new mysqli('localhost', 'root', '', 'hongdu', '3306');
$conn->set_charset('utf8mb4');
if ($conn->connect_error) {
    die("DB connect failed: " . $conn->connect_error . "\n");
}

$result = $conn->query("SELECT * FROM footer_settings WHERE group_key IN ('quick_links','service_links') ORDER BY group_key, sort_order");
while ($row = $result->fetch_assoc()) {
    echo "id=" . $row['id'] . " | group=" . $row['group_key'] . " | key=" . $row['item_key'] . " | label=" . $row['item_label'] . " | value=" . $row['item_value'] . " | url=" . $row['item_url'] . "\n";
}
