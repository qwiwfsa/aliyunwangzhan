<?php
try {
    $pdo = new PDO('mysql:host=127.0.0.1;dbname=hongdu;charset=utf8mb4', 'root', '');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    $stmt = $pdo->query('SELECT * FROM site_styles');
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo "=== site_styles table content ===\n";
    foreach ($rows as $r) {
        echo json_encode($r, JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT) . "\n\n";
    }
    
    // Also check table structure
    $stmt = $pdo->query('DESCRIBE site_styles');
    echo "=== Table structure ===\n";
    foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $col) {
        echo json_encode($col, JSON_UNESCAPED_UNICODE) . "\n";
    }
    
} catch(Exception $e) {
    echo 'Error: ' . $e->getMessage() . "\n";
}
