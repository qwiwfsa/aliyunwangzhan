<?php
/**
 * 文章详情API
 * 获取单篇文章详情
 */

require_once __DIR__ . '/../config.php';

header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, OPTIONS');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($id <= 0) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => '缺少文章ID']);
    exit;
}

$conn = getDbConnection();
initDatabase($conn);

// 获取文章详情
$stmt = $conn->prepare("SELECT a.*, c.name as category_name 
                       FROM cms_articles a 
                       LEFT JOIN cms_categories c ON a.category_id = c.id 
                       WHERE a.id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    $stmt->close();
    $conn->close();
    http_response_code(404);
    echo json_encode(['success' => false, 'message' => '文章不存在']);
    exit;
}

$article = $result->fetch_assoc();

// 处理图片路径
if ($article['cover_image']) {
    $article['cover_image'] = fixImagePath($article['cover_image']);
}

// 更新浏览量
$updateStmt = $conn->prepare("UPDATE cms_articles SET view_count = view_count + 1 WHERE id = ?");
$updateStmt->bind_param("i", $id);
$updateStmt->execute();
$updateStmt->close();

// 获取上一篇和下一篇
$prevStmt = $conn->prepare("SELECT id, title FROM cms_articles WHERE id < ? AND status = 'published' ORDER BY id DESC LIMIT 1");
$prevStmt->bind_param("i", $id);
$prevStmt->execute();
$prevResult = $prevStmt->get_result();
$article['prev'] = $prevResult->fetch_assoc();
$prevStmt->close();

$nextStmt = $conn->prepare("SELECT id, title FROM cms_articles WHERE id > ? AND status = 'published' ORDER BY id ASC LIMIT 1");
$nextStmt->bind_param("i", $id);
$nextStmt->execute();
$nextResult = $nextStmt->get_result();
$article['next'] = $nextResult->fetch_assoc();
$nextStmt->close();

$stmt->close();
$conn->close();

echo json_encode([
    'success' => true,
    'data' => $article
]);

function fixImagePath($path) {
    if (empty($path)) return '';
    if (strpos($path, 'http') === 0) return $path;
    if (strpos($path, '/') === 0) return $path;
    return '../../images/' . $path;
}
