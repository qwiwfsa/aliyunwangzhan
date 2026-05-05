<?php
/**
 * 手机端 - 行业资讯API
 * 从MySQL的cms_articles表读取已发布文章
 */

require_once __DIR__ . '/config.php';
setApiHeaders();
handlePreflight();
requireMethod('GET');

try {
    $db = getDB();
    
    $categoryId = isset($_GET['category_id']) ? intval($_GET['category_id']) : 0;
    
    if ($categoryId > 0) {
        $stmt = $db->prepare("SELECT id, title, summary, content, cover_image, category_id, status, created_at, updated_at FROM cms_articles WHERE status = 'published' AND category_id = :cat ORDER BY updated_at DESC, created_at DESC");
        $stmt->bindParam(':cat', $categoryId, PDO::PARAM_INT);
    } else {
        $stmt = $db->prepare("SELECT id, title, summary, content, cover_image, category_id, status, created_at, updated_at FROM cms_articles WHERE status = 'published' ORDER BY updated_at DESC, created_at DESC");
    }
    
    $stmt->execute();
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    $news = [];
    foreach ($rows as $row) {
        $news[] = [
            'id' => (int)$row['id'],
            'title' => $row['title'],
            'summary' => $row['summary'],
            'content' => $row['content'],
            'cover_image' => $row['cover_image'],
            'category_id' => (int)$row['category_id'],
            'date' => date('Y-m-d', strtotime($row['created_at'])),
            'created_at' => $row['created_at'],
            'updated_at' => $row['updated_at'],
            'status' => 'published'
        ];
    }
    
    // 获取分类列表
    $catStmt = $db->query("SELECT id, name FROM cms_categories ORDER BY sort_order ASC, id ASC");
    $categories = $catStmt->fetchAll(PDO::FETCH_ASSOC);
    
    jsonSuccess([
        'news' => $news,
        'total' => count($news),
        'categories' => $categories
    ]);
    
} catch (Exception $e) {
    jsonError('数据库查询失败: ' . $e->getMessage());
}
