<?php
try {
    $db = new PDO('mysql:host=localhost;dbname=hongdu;charset=utf8mb4', 'root', '');
    $tables = $db->query('SHOW TABLES')->fetchAll(PDO::FETCH_COLUMN);
    echo "Tables:\n" . implode("\n", $tables) . "\n\n";
    
    foreach (['cases', 'cms_articles', 'faq'] as $t) {
        if (in_array($t, $tables)) {
            $cols = $db->query("DESCRIBE $t")->fetchAll(PDO::FETCH_ASSOC);
            echo "--- $t ---\n";
            foreach ($cols as $c) echo $c['Field'] . " " . $c['Type'] . " " . $c['Null'] . " " . ($c['Key'] ?? '') . "\n";
            echo "\n";
        } else {
            echo "Table $t does not exist\n\n";
        }
    }
} catch(Exception $e) {
    echo 'DB Error: ' . $e->getMessage();
}
