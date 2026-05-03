<?php
$conn = @new mysqli('localhost', 'root', '', 'hongdu', '3306');
$conn->set_charset('utf8mb4');
if ($conn->connect_error) die('fail: '.$conn->connect_error);

echo "=== All footer_settings ===\n";
$result = $conn->query("SELECT * FROM footer_settings ORDER BY FIELD(group_key,'brand','quick_links','service_links','contact','bottom'), sort_order");
while ($row = $result->fetch_assoc()) {
    echo "id=".$row['id']." | group=".$row['group_key']." | key=".$row['item_key']." | label=".$row['item_label']." | value='".$row['item_value']."' | url='".$row['item_url']."' | sort=".$row['sort_order']." | updated=".$row['updated_at']."\n";
}
