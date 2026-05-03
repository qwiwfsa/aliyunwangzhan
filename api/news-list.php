<?php
/**
 * 新闻文章列表API
 * 从MySQL的cms_articles表查询已发布的文章
 */

require_once 'config.php';

try {
    // 创建数据库连接
    $pdo = getDB();

    // 获取分类ID参数（可选）
    $categoryId = isset($_GET['category_id']) ? intval($_GET['category_id']) : 0;

    // 构建SQL查询
    if ($categoryId > 0) {
        // 查询指定分类的已发布文章
        $sql = "SELECT id, title, summary, content, excerpt, cover_image, category_id, publish_date, created_at, updated_at, status
                FROM cms_articles
                WHERE status = 'published' AND category_id = :category_id
                ORDER BY publish_date DESC, created_at DESC";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':category_id', $categoryId, PDO::PARAM_INT);
    } else {
        // 查询所有已发布文章
        $sql = "SELECT id, title, summary, content, excerpt, cover_image, category_id, publish_date, created_at, updated_at, status
                FROM cms_articles
                WHERE status = 'published'
                ORDER BY publish_date DESC, created_at DESC";
        $stmt = $pdo->prepare($sql);
    }

    $stmt->execute();
    $articles = $stmt->fetchAll();

    // 返回成功响应
    echo json_encode([
        'success' => true,
        'articles' => $articles,
        'count' => count($articles)
    ], JSON_UNESCAPED_UNICODE);

} catch (PDOException $e) {
    // 返回错误响应
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'error' => '数据库查询失败: ' . $e->getMessage()
    ], JSON_UNESCAPED_UNICODE);
}
