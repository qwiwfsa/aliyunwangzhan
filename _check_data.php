<?php
try {
    $db = new PDO('mysql:host=localhost;dbname=hongdu;charset=utf8mb4', 'root', '');
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "=== cases table ===\n";
    $r = $db->query("SELECT id, title, status, image FROM cases");
    foreach ($r as $row) echo json_encode($row) . "\n";
    
    echo "\n=== cms_articles table ===\n";
    $r = $db->query("SELECT id, title, status, cover_image FROM cms_articles WHERE status != 'deleted'");
    foreach ($r as $row) echo json_encode($row) . "\n";
    
    echo "\n=== faq table ===\n";
    $r = $db->query("SELECT * FROM faq WHERE is_active = 1 OR is_active IS NULL");
    foreach ($r as $row) echo json_encode($row) . "\n";
    
} catch(Exception $e) {
    echo 'DB Error: ' . $e->getMessage();
}
