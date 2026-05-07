<?php
/**
 * 平板端 - 案例详情API
 * 先查MySQL，查不到则从旧JSON文件加载
 */
error_reporting(0);
ini_set('display_errors', 0);

require_once __DIR__ . '/config.php';
setApiHeaders();
handlePreflight();
requireMethod('GET');

$caseIdRaw = isset($_GET['id']) ? trim($_GET['id']) : '';
if (!$caseIdRaw) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => '缺少案例ID']);
    exit;
}

// 尝试从数据库查找（支持带case_前缀和不带）
$dbId = intval(str_replace('case_', '', $caseIdRaw));

try {
    $db = getDB();
    if (!$db) {
        // 数据库不可用，尝试JSON文件
        tryFetchFromJson($caseIdRaw);
        exit;
    }

    if ($dbId > 0) {
        $safeId = intval($dbId);
        $result = $db->query("SELECT id, title, company, amount, period, category, description, image, content, status, sort_order, created_at, updated_at FROM cases WHERE id = $safeId AND status = 1 LIMIT 1");
        $row = $result ? $result->fetch_assoc() : null;

        if ($row) {
            sendCaseFromDbRow($row);
            exit;
        }
    }

    // 数据库查不到，从JSON文件查找
    tryFetchFromJson($caseIdRaw);
    exit;

} catch (Exception $e) {
    // 出错时尝试JSON文件
    tryFetchFromJson($caseIdRaw);
}

function tryFetchFromJson($caseId) {
    // 尝试精确ID文件
    $filePath = DATA_DIR . '/cases/' . $caseId . '.json';
    if (file_exists($filePath)) {
        $data = json_decode(file_get_contents($filePath), true);
        if ($data) {
            sendCaseFromJson($data);
            return;
        }
    }

    // 尝试从case-index.json中查找
    $indexPath = DATA_DIR . '/cases/cases-index.json';
    if (file_exists($indexPath)) {
        $index = json_decode(file_get_contents($indexPath), true);
        if (is_array($index)) {
            foreach ($index as $item) {
                if (is_array($item) && isset($item['id']) && (string)$item['id'] === (string)$caseId) {
                    // 找到对应的case文件
                    $caseFile = DATA_DIR . '/cases/' . $item['id'] . '.json';
                    if (file_exists($caseFile)) {
                        $data = json_decode(file_get_contents($caseFile), true);
                        if ($data) {
                            sendCaseFromJson($data);
                            return;
                        }
                    }
                    // 只用index中的数据
                    sendCaseFromJson($item);
                    return;
                }
            }
        }
    }

    http_response_code(404);
    echo json_encode(['success' => false, 'exists' => false, 'message' => '案例未找到']);
}

function sendCaseFromDbRow($row) {
    $contentData = json_decode($row['content'], true) ?: [];
    $caseData = [
        'id' => (string)$row['id'],
        'title' => $row['title'],
        'type' => $row['category'],
        'city' => $row['company'],
        'amount' => $row['amount'],
        'period' => $row['period'],
        'summary' => $row['description'],
        'image' => $row['image'],
        'coverImage' => $contentData['coverImage'] ?? $row['image'],
        'images' => $contentData['images'] ?? [],
        'detail' => $contentData['detail'] ?? $row['description'],
        'highlights' => $contentData['highlights'] ?? [],
        'process' => $contentData['process'] ?? [],
        'hasVideo' => $contentData['hasVideo'] ?? false,
        'video' => $contentData['video'] ?? '',
        'status' => 'published',
        'lastModified' => $row['updated_at']
    ];
    jsonSuccess(['case' => $caseData]);
}

function sendCaseFromJson($item) {
    $images = [];
    if (!empty($item['images'])) {
        $images = is_array($item['images']) ? $item['images'] : [$item['images']];
    } elseif (!empty($item['image'])) {
        $images = [$item['image']];
    }
    if (!empty($item['coverImage'])) {
        array_unshift($images, $item['coverImage']);
        $images = array_unique($images);
    }

    $caseData = [
        'id' => (string)$item['id'],
        'title' => $item['title'] ?? '',
        'type' => $item['type'] ?? '',
        'city' => $item['city'] ?? '',
        'amount' => $item['amount'] ?? '',
        'period' => $item['period'] ?? '',
        'summary' => $item['summary'] ?? '',
        'image' => $item['image'] ?? '',
        'coverImage' => $item['coverImage'] ?? $item['image'] ?? '',
        'images' => $images,
        'detail' => $item['detail'] ?? $item['summary'] ?? '',
        'highlights' => $item['highlights'] ?? [],
        'process' => $item['process'] ?? [],
        'hasVideo' => $item['hasVideo'] ?? false,
        'video' => $item['video'] ?? '',
        'status' => 'published',
        'lastModified' => $item['lastModified'] ?? date('Y-m-d H:i:s')
    ];
    jsonSuccess(['case' => $caseData]);
}
