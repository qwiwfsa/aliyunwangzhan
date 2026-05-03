<?php
$conn = new mysqli('localhost', 'root', '', 'hongdu');
if ($conn->connect_error) { die('Connection failed: ' . $conn->connect_error); }
$result = $conn->query('SELECT id, title, status, cover_image, created_at FROM cms_articles ORDER BY id');
echo 'Total rows: ' . $result->num_rows . "\n";
while($row = $result->fetch_assoc()) {
    echo "  ID=" . $row['id'] . " | " . mb_substr($row['title'],0,30) . " | status=" . $row['status'] . " | cover=" . ($row['cover_image'] ?: 'NULL') . " | created=" . $row['created_at'] . "\n";
}
$conn->close();
