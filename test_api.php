<?php
$_SERVER['SERVER_ADDR'] = '127.0.0.1';
$_SERVER['HTTP_HOST'] = 'localhost';
$_SERVER['REQUEST_METHOD'] = 'GET';
ob_start();
include 'api/news-list.php';
$output = ob_get_clean();
$data = json_decode($output, true);
echo 'Success: ' . ($data['success'] ? 'true' : 'false') . PHP_EOL;
echo 'Count: ' . $data['count'] . PHP_EOL;
foreach ($data['articles'] as $a) {
    echo 'ID:' . $a['id'] . ' | Title:' . $a['title'] . ' | Cat:' . $a['category_id'] . PHP_EOL;
}
