<?php
/**
 * 文章分类API
 * 获取、创建、更新、删除分类
 */

require_once __DIR__ . '/../config.php';

header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

$method = $_SERVER['REQUEST_METHOD'];
$conn = getDbConnection();
initDatabase($conn);

switch ($method) {
    case 'GET':
        // 获取分类列表
        $result = $conn->query("SELECT * FROM cms_categories ORDER BY sort_order ASC, id ASC");
        $categories = [];
        while ($row = $result->fetch_assoc()) {
            $categories[] = $row;
        }
        echo json_encode(['success' => true, 'data' => $categories]);
        break;
        
    case 'POST':
        // 创建分类
        $rawData = file_get_contents('php://input');
        $data = json_decode($rawData, true);
        
        if (empty($data['name'])) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => '分类名称不能为空']);
            break;
        }
        
        $name = trim($data['name']);
        $description = isset($data['description']) ? trim($data['description']) : '';
        $sortOrder = isset($data['sort_order']) ? intval($data['sort_order']) : 0;
        
        $stmt = $conn->prepare("INSERT INTO cms_categories (name, description, sort_order) VALUES (?, ?, ?)");
        $stmt->bind_param("ssi", $name, $description, $sortOrder);
        
        if ($stmt->execute()) {
            echo json_encode([
                'success' => true, 
                'message' => '分类创建成功',
                'data' => ['id' => $stmt->insert_id]
            ]);
        } else {
            http_response_code(500);
            echo json_encode(['success' => false, 'message' => '创建失败: ' . $stmt->error]);
        }
        $stmt->close();
        break;
        
    case 'PUT':
        // 更新分类
        $rawData = file_get_contents('php://input');
        $data = json_decode($rawData, true);
        
        $id = isset($data['id']) ? intval($data['id']) : 0;
        if ($id <= 0 || empty($data['name'])) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => '参数错误']);
            break;
        }
        
        $name = trim($data['name']);
        $description = isset($data['description']) ? trim($data['description']) : '';
        $sortOrder = isset($data['sort_order']) ? intval($data['sort_order']) : 0;
        
        $stmt = $conn->prepare("UPDATE cms_categories SET name = ?, description = ?, sort_order = ? WHERE id = ?");
        $stmt->bind_param("ssii", $name, $description, $sortOrder, $id);
        
        if ($stmt->execute()) {
            echo json_encode(['success' => true, 'message' => '分类更新成功']);
        } else {
            http_response_code(500);
            echo json_encode(['success' => false, 'message' => '更新失败: ' . $stmt->error]);
        }
        $stmt->close();
        break;
        
    case 'DELETE':
        // 删除分类
        $rawData = file_get_contents('php://input');
        $data = json_decode($rawData, true);
        
        $id = isset($data['id']) ? intval($data['id']) : 0;
        if ($id <= 0) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => '缺少分类ID']);
            break;
        }
        
        // 检查分类下是否有文章
        $checkStmt = $conn->prepare("SELECT COUNT(*) as count FROM cms_articles WHERE category_id = ? AND status != 'deleted'");
        $checkStmt->bind_param("i", $id);
        $checkStmt->execute();
        $count = $checkStmt->get_result()->fetch_assoc()['count'];
        $checkStmt->close();
        
        if ($count > 0) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => '该分类下还有文章，无法删除']);
            break;
        }
        
        $stmt = $conn->prepare("DELETE FROM cms_categories WHERE id = ?");
        $stmt->bind_param("i", $id);
        
        if ($stmt->execute()) {
            echo json_encode(['success' => true, 'message' => '分类删除成功']);
        } else {
            http_response_code(500);
            echo json_encode(['success' => false, 'message' => '删除失败: ' . $stmt->error]);
        }
        $stmt->close();
        break;
}

$conn->close();
