<?php
/**
 * 桌面端 - 行业资讯API
 * 从MySQL的cms_articles表读取已发布文章
 */

require_once __DIR__ . '/config.php';
setApiHeaders();
handlePreflight();
requireMethod('GET');

try {
    $db = getDB();
    
    $categoryId = isset($_GET['category_id']) ? intval($_GET['category_id']) : 0;
    $page = isset($_GET['page']) ? intval($_GET['page']) : 1;
    $limit = isset($_GET['limit']) ? intval($_GET['limit']) : 10;
    if ($page < 1) $page = 1;
    if ($limit < 1 || $limit > 50) $limit = 10;
    $offset = ($page - 1) * $limit;
    
    // 获取总数
    if ($categoryId > 0) {
        $countStmt = $db->prepare("SELECT COUNT(*) as total FROM cms_articles WHERE status = 'published' AND category_id = ?");
        $countStmt->bind_param('i', $categoryId);
    } else {
        $countStmt = $db->prepare("SELECT COUNT(*) as total FROM cms_articles WHERE status = 'published'");
    }
    $countStmt->execute();
    $totalResult = $countStmt->get_result();
    $total = $totalResult->fetch_assoc()['total'];
    $countStmt->close();
    
    if ($categoryId > 0) {
        $stmt = $db->prepare("SELECT id, title, summary, content, cover_image, category_id, status, created_at, updated_at FROM cms_articles WHERE status = 'published' AND category_id = ? ORDER BY updated_at DESC, created_at DESC LIMIT ? OFFSET ?");
        $stmt->bind_param('iii', $categoryId, $limit, $offset);
    } else {
        $stmt = $db->prepare("SELECT id, title, summary, content, cover_image, category_id, status, created_at, updated_at FROM cms_articles WHERE status = 'published' ORDER BY updated_at DESC, created_at DESC LIMIT ? OFFSET ?");
        $stmt->bind_param('ii', $limit, $offset);
    }
    
    $stmt->execute();
    $result = $stmt->get_result();
    $rows = $result->fetch_all(MYSQLI_ASSOC);
    $stmt->close();
    
    $news = [];
    foreach ($rows as $row) {
        $coverImage = $row['cover_image'];
        if ($coverImage) {
            if (strpos($coverImage, 'http') === 0) {
                // HTTP路径，保持不变
            } elseif (strpos($coverImage, '/') === 0) {
                // 已经是绝对路径
            } elseif (strpos($coverImage, 'uploads/') === 0) {
                // 数据库存的是 uploads/xxx.jpg，加/变成绝对路径
                $coverImage = '/' . $coverImage;
            } else {
                // 纯文件名，补上 uploads/
                $coverImage = '/uploads/' . ltrim($coverImage, '/');
            }
        }
        
        $news[] = [
            'id' => (int)$row['id'],
            'title' => $row['title'],
            'summary' => $row['summary'],
            'content' => $row['content'],
            'cover_image' => $coverImage,
            'category_id' => (int)$row['category_id'],
            'date' => date('Y-m-d', strtotime($row['created_at'])),
            'created_at' => $row['created_at'],
            'updated_at' => $row['updated_at'],
            'status' => 'published'
        ];
    }
    
    // 获取分类列表
    $catResult = $db->query("SELECT id, name FROM cms_categories ORDER BY sort_order ASC, id ASC");
    $categories = [];
    if ($catResult) {
        $categories = $catResult->fetch_all(MYSQLI_ASSOC);
    }
    
    jsonSuccess([
        'news' => $news,
        'total' => (int)$total,
        'page' => $page,
        'limit' => $limit,
        'totalPages' => ceil($total / $limit),
        'categories' => $categories
    ]);
    
} catch (Exception $e) {
    jsonError('数据库查询失败: ' . $e->getMessage());
}
