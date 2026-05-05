<?php
/**
 * 案例保存API
 * 直接保存到MySQL，废弃JSON数据源
 */

error_reporting(E_ALL);
ini_set('display_errors', 0);

header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => '方法不允许']);
    exit;
}

require_once __DIR__ . '/../../config/db.php';

$json = file_get_contents('php://input');
$data = json_decode($json, true);

if (!$data) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => '无效的JSON数据']);
    exit;
}

$caseId = isset($data['id']) ? intval(preg_replace('/[^0-9]/', '', $data['id'])) : 0;
$title = $data['title'] ?? '';
$category = $data['type'] ?? '';
$company = $data['city'] ?? '';
$amount = $data['amount'] ?? '';
$period = $data['period'] ?? '';
$description = $data['summary'] ?? '';
$image = $data['image'] ?? $data['coverImage'] ?? '';
$status = (isset($data['status']) && $data['status'] === 'published') ? 1 : 0;

// Content JSON stores rich data not in DB columns
$contentData = [
    'detail' => $data['detail'] ?? $data['summary'] ?? '',
    'highlights' => $data['highlights'] ?? [],
    'process' => $data['process'] ?? [],
    'images' => $data['images'] ?? [],
    'coverImage' => $data['coverImage'] ?? $data['image'] ?? '',
    'hasVideo' => $data['hasVideo'] ?? false,
    'video' => $data['video'] ?? '',
    'original_id' => $data['id'] ?? ''
];

// If no main image but images array exists, use first
if (empty($image) && !empty($data['images']) && is_array($data['images'])) {
    $image = $data['images'][0];
    $contentData['coverImage'] = $image;
}

$content = json_encode($contentData, JSON_UNESCAPED_UNICODE);

try {
    $conn = getDB();
    
    if ($caseId > 0) {
        // Check if exists
        $check = $conn->prepare("SELECT id FROM cases WHERE id = ?");
        $check->bind_param("i", $caseId);
        $check->execute();
        $exists = $check->get_result()->fetch_assoc();
        $check->close();
        
        if ($exists) {
            $stmt = $conn->prepare("UPDATE cases SET title=?, category=?, company=?, amount=?, period=?, description=?, image=?, content=?, status=?, updated_at=NOW() WHERE id=?");
            $stmt->bind_param("sssssssssi", $title, $category, $company, $amount, $period, $description, $image, $content, $status, $caseId);
        } else {
            $stmt = $conn->prepare("UPDATE cases SET title=?, category=?, company=?, amount=?, period=?, description=?, image=?, content=?, status=?, updated_at=NOW() WHERE id=?");
            $stmt->bind_param("sssssssssi", $title, $category, $company, $amount, $period, $description, $image, $content, $status, $caseId);
        }
    } else {
        // Get next available ID
        $maxId = $conn->query("SELECT COALESCE(MAX(id), 0) + 1 AS next_id FROM cases")->fetch_assoc()['next_id'];
        $caseId = $maxId;
        
        $stmt = $conn->prepare("INSERT INTO cases (id, title, category, company, amount, period, description, image, content, status, sort_order, created_at, updated_at) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 0, NOW(), NOW())");
        $stmt->bind_param("issssssssi", $caseId, $title, $category, $company, $amount, $period, $description, $image, $content, $status);
    }
    
    if ($stmt->execute()) {
        $stmt->close();
        $conn->close();
        
        echo json_encode([
            'success' => true,
            'message' => '案例保存成功',
            'case' => [
                'id' => (string)$caseId,
                'title' => $title,
                'type' => $category,
                'city' => $company,
                'amount' => $amount,
                'period' => $period,
                'summary' => $description,
                'image' => $image,
                'coverImage' => $contentData['coverImage'],
                'images' => $contentData['images'],
                'detail' => $contentData['detail'],
                'highlights' => $contentData['highlights'],
                'process' => $contentData['process'],
                'hasVideo' => $contentData['hasVideo'],
                'video' => $contentData['video'],
                'status' => $status ? 'published' : 'draft',
                'lastModified' => date('Y-m-d H:i:s')
            ]
        ], JSON_UNESCAPED_UNICODE);
    } else {
        $error = $stmt->error;
        $stmt->close();
        $conn->close();
        http_response_code(500);
        echo json_encode(['success' => false, 'message' => '保存失败: ' . $error]);
    }
    
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => '服务器错误: ' . $e->getMessage()]);
}
