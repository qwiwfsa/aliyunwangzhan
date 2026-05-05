<?php
// Migrate JSON cases to MySQL
try {
    $db = new PDO('mysql:host=localhost;dbname=hongdu;charset=utf8mb4', 'root', '');
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Read all JSON case files
    $dataDir = __DIR__ . '/data/cases/';
    $indexFile = __DIR__ . '/data/cases-index.json';
    
    $cases = [];
    if (file_exists($indexFile)) {
        $json = file_get_contents($indexFile);
        $cases = json_decode($json, true) ?: [];
    }
    
    foreach ($cases as $case) {
        // Map JSON fields to DB fields
        $id = isset($case['id']) ? preg_replace('/[^0-9]/', '', $case['id']) : 0;
        if (!$id) {
            echo "Skipping case without numeric ID: " . json_encode($case['id']) . "\n";
            continue;
        }
        
        $title = $case['title'] ?? '';
        $company = $case['city'] ?? '';
        $amount = $case['amount'] ?? '';
        $period = $case['period'] ?? '';
        $category = $case['type'] ?? '';
        $description = $case['summary'] ?? '';
        $content = $case['detail'] ?? $case['summary'] ?? '';
        $image = $case['image'] ?? $case['coverImage'] ?? '';
        $status = ($case['status'] ?? 'draft') === 'published' ? 1 : 0;
        $sortOrder = 0;
        $createdAt = $case['lastModified'] ?? date('Y-m-d H:i:s');
        $updatedAt = $case['lastModified'] ?? date('Y-m-d H:i:s');
        
        // Check if exists
        $stmt = $db->prepare("SELECT id FROM cases WHERE id = ?");
        $stmt->execute([$id]);
        $exists = $stmt->fetch();
        
        if ($exists) {
            $stmt = $db->prepare("UPDATE cases SET title=?, company=?, amount=?, period=?, category=?, description=?, content=?, image=?, status=?, sort_order=?, updated_at=? WHERE id=?");
            $stmt->execute([$title, $company, $amount, $period, $category, $description, $content, $image, $status, $sortOrder, $updatedAt, $id]);
            echo "Updated case $id: $title\n";
        } else {
            $stmt = $db->prepare("INSERT INTO cases (id, title, company, amount, period, category, description, content, image, status, sort_order, created_at, updated_at) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt->execute([$id, $title, $company, $amount, $period, $category, $description, $content, $image, $status, $sortOrder, $createdAt, $updatedAt]);
            echo "Inserted case $id: $title\n";
        }
    }
    
    echo "\nMigration complete!\n";
    
    // Verify
    $r = $db->query("SELECT id, title, status, image FROM cases ORDER BY id");
    echo "\n=== Current cases in DB ===\n";
    foreach ($r as $row) echo json_encode($row, JSON_UNESCAPED_UNICODE) . "\n";
    
} catch(Exception $e) {
    echo 'Error: ' . $e->getMessage();
}
