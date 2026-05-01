<?php
/**
 * 案例管理API
 */

require_once 'config.php';

$method = $_SERVER['REQUEST_METHOD'];
$db = getDB();

switch ($method) {
    case 'GET':
        // 获取案例列表或单个案例
        if (isset($_GET['id'])) {
            $stmt = $db->prepare("SELECT * FROM cases WHERE id = ?");
            $stmt->execute([$_GET['id']]);
            $case = $stmt->fetch();
            
            if ($case) {
                response($case);
            } else {
                error('案例不存在', 404);
            }
        } else {
            // 获取案例列表
            $category = $_GET['category'] ?? null;
            
            if ($category) {
                $stmt = $db->prepare("SELECT * FROM cases WHERE category = ? AND status = 1 ORDER BY sort_order, id DESC");
                $stmt->execute([$category]);
            } else {
                $stmt = $db->query("SELECT * FROM cases ORDER BY sort_order, id DESC");
            }
            
            $cases = $stmt->fetchAll();
            response($cases);
        }
        break;
        
    case 'POST':
        // 创建新案例
        $input = getInput();
        
        if (empty($input['title'])) {
            error('案例标题不能为空');
        }
        
        try {
            $stmt = $db->prepare("INSERT INTO cases (title, company, amount, period, category, description, content, image, status, sort_order) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt->execute([
                $input['title'],
                $input['company'] ?? '',
                $input['amount'] ?? '',
                $input['period'] ?? '',
                $input['category'] ?? '',
                $input['description'] ?? '',
                $input['content'] ?? '',
                $input['image'] ?? '',
                $input['status'] ?? 1,
                $input['sort_order'] ?? 0
            ]);
            
            $id = $db->lastInsertId();
            response(['id' => $id, 'message' => '案例创建成功']);
        } catch (PDOException $e) {
            error('创建失败: ' . $e->getMessage());
        }
        break;
        
    case 'PUT':
        // 更新案例
        $input = getInput();
        
        if (empty($input['id'])) {
            error('案例ID不能为空');
        }
        
        try {
            $stmt = $db->prepare("UPDATE cases SET title = ?, company = ?, amount = ?, period = ?, category = ?, description = ?, content = ?, image = ?, status = ?, sort_order = ? WHERE id = ?");
            $stmt->execute([
                $input['title'] ?? '',
                $input['company'] ?? '',
                $input['amount'] ?? '',
                $input['period'] ?? '',
                $input['category'] ?? '',
                $input['description'] ?? '',
                $input['content'] ?? '',
                $input['image'] ?? '',
                $input['status'] ?? 1,
                $input['sort_order'] ?? 0,
                $input['id']
            ]);
            
            response(['message' => '案例更新成功']);
        } catch (PDOException $e) {
            error('更新失败: ' . $e->getMessage());
        }
        break;
        
    case 'DELETE':
        // 删除案例
        $input = getInput();
        
        if (empty($input['id'])) {
            error('案例ID不能为空');
        }
        
        try {
            $stmt = $db->prepare("DELETE FROM cases WHERE id = ?");
            $stmt->execute([$input['id']]);
            
            response(['message' => '案例删除成功']);
        } catch (PDOException $e) {
            error('删除失败: ' . $e->getMessage());
        }
        break;
        
    default:
        error('不支持的请求方法', 405);
}
