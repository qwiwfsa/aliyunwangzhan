<?php
$conn = @new mysqli('localhost', 'root', '', 'hongdu', 3306);
$conn->set_charset('utf8mb4');
if ($conn->connect_error) { die('ERROR: '.$conn->connect_error); }
$r = $conn->query('SELECT id, title, summary, LEFT(content,100) as content_sample, view_count FROM cms_articles ORDER BY id DESC LIMIT 10');
while ($row = $r->fetch_assoc()) {
    echo "ID: ".$row['id']."\n";
    echo "Title: ".$row['title']."\n";
    echo "Content: ".$row['content_sample']."\n";
    echo "---\n";
}
