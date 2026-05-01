<?php
/**
 * CMS删除页面API
 * 从数据库删除页面
 */

// 引入数据库配置
require_once __DIR__ . '/config.php';

// 设置响应头
header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

// 处理预检请求
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

// 允许POST和DELETE请求
if ($_SERVER['REQUEST_METHOD'] !== 'POST' && $_SERVER['REQUEST_METHOD'] !== 'DELETE') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => '方法不允许']);
    exit;
}

// 获取请求数据
if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
    $json = file_get_contents('php://input');
    $data = json_decode($json, true);
} else {
    $data = $_POST;
}

// 验证必需字段
$pageId = isset($data['pageId']) ? $data['pageId'] : (isset($_GET['pageId']) ? $_GET['pageId'] : '');

if (empty($pageId)) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => '缺少页面ID']);
    exit;
}

// 清理页面ID
$pageId = preg_replace('/[^a-zA-Z0-9_-]/', '', $pageId);

// 保护关键页面不被删除
$protectedPages = ['index', 'services', 'cases', 'contact', 'about'];
if (in_array($pageId, $protectedPages)) {
    http_response_code(403);
    echo json_encode(['success' => false, 'message' => '该页面受保护，无法删除']);
    exit;
}

// 获取数据库连接
$conn = getDbConnection();

// 检查页面是否存在
$checkStmt = $conn->prepare("SELECT id, page_name FROM cms_pages WHERE page_id = ?");
$checkStmt->bind_param("s", $pageId);
$checkStmt->execute();
$checkResult = $checkStmt->get_result();

if ($checkResult->num_rows === 0) {
    $checkStmt->close();
    $conn->close();
    http_response_code(404);
    echo json_encode(['success' => false, 'message' => '页面不存在']);
    exit;
}

$pageInfo = $checkResult->fetch_assoc();
$checkStmt->close();

// 删除页面
$deleteStmt = $conn->prepare("DELETE FROM cms_pages WHERE page_id = ?");
$deleteStmt->bind_param("s", $pageId);
$result = $deleteStmt->execute();
$deleteStmt->close();
$conn->close();

if (!$result) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => '删除页面失败']);
    exit;
}

// 更新cms/content.json
removeFromPagesIndex($pageId);

echo json_encode([
    'success' => true,
    'message' => '页面删除成功',
    'data' => [
        'pageId' => $pageId,
        'pageName' => $pageInfo['page_name'],
        'deletedAt' => date('Y-m-d H:i:s')
    ]
]);

/**
 * 从页面索引中移除
 */
function removeFromPagesIndex($pageId) {
    $contentFile = __DIR__ . '/../../cms/content.json';
    
    if (!file_exists($contentFile)) {
        return;
    }
    
    $json = file_get_contents($contentFile);
    $content = json_decode($json, true);
    
    if (isset($content['pages'][$pageId])) {
        unset($content['pages'][$pageId]);
        $content['lastUpdated'] = date('c');
        file_put_contents($contentFile, json_encode($content, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
    }
}
