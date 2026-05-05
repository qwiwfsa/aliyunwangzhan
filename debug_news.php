<?php
require_once 'api/config.php';
header('Content-Type: text/plain; charset=utf-8');

try {
    $pdo = getDB();
    
    echo "=== news表数据 ===\n";
    $stmt = $pdo->query("SELECT id, title, status, created_at FROM news ORDER BY id");
    $news = $stmt->fetchAll();
    echo "共 " . count($news) . " 条记录\n";
    foreach($news as $n) {
        echo "ID: {$n['id']} | 标题: {$n['title']} | 状态: {$n['status']}\n";
    }
    
    echo "\n=== cms_articles表数据 ===\n";
    $stmt = $pdo->query("SELECT id, title, status, created_at FROM cms_articles ORDER BY id");
    $cms = $stmt->fetchAll();
    echo "共 " . count($cms) . " 条记录\n";
    foreach($cms as $c) {
        echo "ID: {$c['id']} | 标题: {$c['title']} | 状态: {$c['status']}\n";
    }
    
} catch (Exception $e) {
    echo "错误: " . $e->getMessage();
}
