<?php
// Migrate JSON cases to MySQL - handling int max issues
try {
    $db = new PDO('mysql:host=localhost;dbname=hongdu;charset=utf8mb4', 'root', '');
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    $indexFile = __DIR__ . '/data/cases-index.json';
    $cases = json_decode(file_get_contents($indexFile), true) ?: [];
    
    // Clear existing test data
    $db->exec("DELETE FROM cases WHERE id > 3");
    
    $seq = 10; // start after existing DB entries (id 1-3)
    
    foreach ($cases as $case) {
        $title = $case['title'] ?? '';
        $company = $case['city'] ?? '';
        $amount = $case['amount'] ?? '';
        $period = $case['period'] ?? '';
        $category = $case['type'] ?? '';
        $description = $case['summary'] ?? '';
        $content_json = json_encode([
            'detail' => $case['detail'] ?? $case['summary'] ?? '',
            'highlights' => $case['highlights'] ?? [],
            'process' => $case['process'] ?? [],
            'images' => $case['images'] ?? [],
            'coverImage' => $case['coverImage'] ?? $case['image'] ?? '',
            'hasVideo' => $case['hasVideo'] ?? false,
            'video' => $case['video'] ?? '',
            'original_id' => $case['id'] ?? ''
        ], JSON_UNESCAPED_UNICODE);
        
        $image = $case['image'] ?? $case['coverImage'] ?? '';
        $status = ($case['status'] ?? 'draft') === 'published' ? 1 : 0;
        $sortOrder = 0;
        $createdAt = $case['lastModified'] ?? date('Y-m-d H:i:s');
        $updatedAt = $case['lastModified'] ?? date('Y-m-d H:i:s');
        
        $stmt = $db->prepare("INSERT INTO cases (id, title, company, amount, period, category, description, content, image, status, sort_order, created_at, updated_at) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([$seq, $title, $company, $amount, $period, $category, $description, $content_json, $image, $status, $sortOrder, $createdAt, $updatedAt]);
        echo "Inserted case $seq: $title\n";
        $seq++;
    }
    
    echo "\nMigration complete! ($seq entries)\n";
    
    // Verify
    $r = $db->query("SELECT id, title, status, LEFT(image,50) as img FROM cases ORDER BY id");
    foreach ($r as $row) echo "  [{$row['id']}] {$row['title']} (status={$row['status']})\n";
    
} catch(Exception $e) {
    echo 'Error: ' . $e->getMessage();
}
