<?php
/**
 * 文章列表API
 * 获取文章列表，支持分类筛选和分页
 */

require_once __DIR__ . '/../config.php';

header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, OPTIONS');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

// 获取参数
$category = isset($_GET['category']) ? intval($_GET['category']) : 0;
$page = isset($_GET['page']) ? intval($_GET['page']) : 1;
$limit = isset($_GET['limit']) ? intval($_GET['limit']) : 10;
$keyword = isset($_GET['keyword']) ? trim($_GET['keyword']) : '';
$status = isset($_GET['status']) ? $_GET['status'] : 'all';

// 限制每页数量
if ($limit < 1 || $limit > 100) $limit = 10;
if ($page < 1) $page = 1;

$offset = ($page - 1) * $limit;

$conn = getDbConnection();
initDatabase($conn);

// 构建查询
$where = ["1=1"];
$params = [];
$types = "";

if ($category > 0) {
    $where[] = "category_id = ?";
    $params[] = $category;
    $types .= "i";
}

if ($keyword) {
    $where[] = "(title LIKE ? OR summary LIKE ? OR content LIKE ?)";
    $searchTerm = "%$keyword%";
    $params[] = $searchTerm;
    $params[] = $searchTerm;
    $params[] = $searchTerm;
    $types .= "sss";
}

if ($status === 'published') {
    $where[] = "status = 'published'";
} elseif ($status === 'draft') {
    $where[] = "status = 'draft'";
}

$whereClause = implode(" AND ", $where);

// 获取总数
$countSql = "SELECT COUNT(*) as total FROM cms_articles WHERE $whereClause";
$stmt = $conn->prepare($countSql);
if (!empty($params)) {
    $stmt->bind_param($types, ...$params);
}
$stmt->execute();
$total = $stmt->get_result()->fetch_assoc()['total'];
$stmt->close();

// 获取文章列表
$sql = "SELECT a.*, c.name as category_name 
        FROM cms_articles a 
        LEFT JOIN cms_categories c ON a.category_id = c.id 
        WHERE $whereClause 
        ORDER BY a.is_top DESC, a.sort_order ASC, a.created_at DESC 
        LIMIT ? OFFSET ?";

$stmt = $conn->prepare($sql);
$params[] = $limit;
$params[] = $offset;
$types .= "ii";
$stmt->bind_param($types, ...$params);
$stmt->execute();
$result = $stmt->get_result();

$articles = [];
while ($row = $result->fetch_assoc()) {
    // 处理图片路径
    if ($row['cover_image']) {
        $row['cover_image'] = fixImagePath($row['cover_image']);
    }
    $articles[] = $row;
}

$stmt->close();
$conn->close();

echo json_encode([
    'success' => true,
    'data' => $articles,
    'pagination' => [
        'page' => $page,
        'limit' => $limit,
        'total' => $total,
        'totalPages' => ceil($total / $limit)
    ]
]);

/**
 * 修复图片路径
 */
function fixImagePath($path) {
    if (empty($path)) return '';
    if (strpos($path, 'http') === 0) return $path;
    if (strpos($path, '/') === 0) return $path;
    return '../../images/' . $path;
}
