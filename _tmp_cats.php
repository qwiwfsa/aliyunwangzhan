<?php
require_once 'config/db.php';
$conn = getDB();
$sql = "SELECT cat_key, cat_label, sort_order FROM faq_categories ORDER BY sort_order ASC";
$result = $conn->query($sql);
$cats = [];
while ($row = $result->fetch_assoc()) {
    $cats[] = $row;
}
echo json_encode($cats, JSON_UNESCAPED_UNICODE);
