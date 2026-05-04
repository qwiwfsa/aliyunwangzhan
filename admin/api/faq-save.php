<?php
/**
 * FAQ保存 API
 * POST: 保存/更新/删除FAQ
 */

require_once __DIR__ . '/../../config/db.php';

header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

// 处理预检请求
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

$conn = getDB();

// ========== POST 处理 ==========
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = isset($_POST['action']) ? $_POST['action'] : '';

    // --- 删除FAQ ---
    if ($action === 'delete') {
        $id = isset($_POST['id']) ? intval($_POST['id']) : 0;
        if (!$id) {
            echo json_encode(['code' => 1, 'msg' => '缺少ID参数']);
            exit;
        }
        $stmt = $conn->prepare("DELETE FROM faq WHERE id=?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $stmt->close();
        echo json_encode(['code' => 0, 'msg' => '删除成功']);
        exit;
    }

    // --- 保存FAQ（新增或更新） ---
    if ($action === 'save') {
        $id = isset($_POST['id']) ? intval($_POST['id']) : 0;
        $question = isset($_POST['question']) ? trim($_POST['question']) : '';
        $answer = isset($_POST['answer']) ? trim($_POST['answer']) : '';
        $category = isset($_POST['category']) ? trim($_POST['category']) : 'general';

        if (!$question) {
            echo json_encode(['code' => 1, 'msg' => '问题不能为空']);
            exit;
        }

        if (!$answer) {
            echo json_encode(['code' => 1, 'msg' => '答案不能为空']);
            exit;
        }

        if ($id > 0) {
            // 更新现有FAQ
            $stmt = $conn->prepare("UPDATE faq SET question=?, answer=?, category=? WHERE id=?");
            $stmt->bind_param("sssi", $question, $answer, $category, $id);
            $stmt->execute();
            $stmt->close();
            echo json_encode(['code' => 0, 'msg' => '更新成功', 'id' => $id]);
        } else {
            // 新增FAQ
            $stmt = $conn->prepare("INSERT INTO faq (question, answer, category) VALUES (?, ?, ?)");
            $stmt->bind_param("sss", $question, $answer, $category);
            $stmt->execute();
            $newId = $conn->insert_id;
            $stmt->close();
            echo json_encode(['code' => 0, 'msg' => '添加成功', 'id' => $newId]);
        }
        exit;
    }

    echo json_encode(['code' => 1, 'msg' => '未知操作']);
    exit;
}

echo json_encode(['code' => 1, 'msg' => '仅支持POST请求']);
