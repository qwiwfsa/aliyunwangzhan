<?php
/**
 * CMS页面发布API
 * 将数据标记为已发布状态
 */

// 引入数据库配置
require_once __DIR__ . '/config.php';

// 设置响应头
header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

// 处理预检请求
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

// 只允许POST请求
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => '方法不允许']);
    exit;
}

// 获取POST数据
$json = file_get_contents('php://input');
$data = json_decode($json, true);

if (!$data) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => '无效的JSON数据']);
    exit;
}

// 验证必需字段
if (empty($data['page'])) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => '缺少页面ID']);
    exit;
}

$pageId = preg_replace('/[^a-zA-Z0-9_-]/', '', $data['page']);

// 页面映射配置
$pageMap = [
    'index' => 'index.html',
    'services' => 'services.html',
    'cases' => 'cases.html',
    'case-detail' => 'case-detail.html',
    'advantages' => 'advantages.html',
    'news' => 'news.html',
    'faq' => 'faq.html',
    'contact' => 'contact.html',
    'privacy' => 'privacy.html',
    'compliance' => 'compliance.html',
    'sitemap' => 'sitemap.html'
];

if (!isset($pageMap[$pageId])) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => '未知的页面ID']);
    exit;
}

// 获取数据库连接
$conn = getDbConnection();

// 初始化数据库（确保表存在）
initDatabase($conn);

// 添加发布标记到数据中
$data['published'] = true;
$data['publishedAt'] = date('Y-m-d H:i:s');
$data['lastModified'] = date('Y-m-d H:i:s');

$pageName = isset($data['pageName']) ? $data['pageName'] : $pageId;
$title = isset($data['title']) ? $data['title'] : '';
$subtitle = isset($data['subtitle']) ? $data['subtitle'] : '';

// 准备内容JSON
$contentJson = json_encode($data, JSON_UNESCAPED_UNICODE);

// 检查页面是否已存在
$checkStmt = $conn->prepare("SELECT id FROM cms_pages WHERE page_id = ?");
$checkStmt->bind_param("s", $pageId);
$checkStmt->execute();
$checkResult = $checkStmt->get_result();

if ($checkResult->num_rows > 0) {
    // 更新现有页面
    $updateStmt = $conn->prepare("UPDATE cms_pages SET page_name = ?, title = ?, subtitle = ?, content = ? WHERE page_id = ?");
    $updateStmt->bind_param("sssss", $pageName, $title, $subtitle, $contentJson, $pageId);
    $result = $updateStmt->execute();
    $updateStmt->close();
} else {
    // 插入新页面
    $insertStmt = $conn->prepare("INSERT INTO cms_pages (page_id, page_name, title, subtitle, content) VALUES (?, ?, ?, ?, ?)");
    $insertStmt->bind_param("sssss", $pageId, $pageName, $title, $subtitle, $contentJson);
    $result = $insertStmt->execute();
    $insertStmt->close();
}

$checkStmt->close();
$conn->close();

if (!$result) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => '发布失败']);
    exit;
}

echo json_encode([
    'success' => true,
    'message' => '发布成功',
    'page' => $pageId,
    'file' => $pageMap[$pageId],
    'publishedAt' => $data['publishedAt']
]);
