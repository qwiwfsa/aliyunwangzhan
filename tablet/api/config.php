<?php
// 关闭所有错误显示，防止任何PHP警告/通知变成HTML输出
error_reporting(0);
ini_set('display_errors', 0);

define('SITE_ROOT', realpath(__DIR__ . '/../../'));
define('DATA_DIR', SITE_ROOT . '/data');

// 使用统一的数据库配置（mysqli），会自动根据环境选择本地或服务器凭据
require_once __DIR__ . '/../../config/db.php';

function setApiHeaders() {
    // 清理所有之前可能的输出
    if (ob_get_level()) { ob_clean(); }
    header('Content-Type: application/json; charset=utf-8');
    header('Access-Control-Allow-Origin: *');
    header('Access-Control-Allow-Methods: GET, OPTIONS');
    header('Access-Control-Allow-Headers: Content-Type');
    header('Cache-Control: no-cache, no-store, must-revalidate');
    header('Pragma: no-cache');
    header('Expires: 0');
}

function handlePreflight() {
    if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
        http_response_code(200);
        exit;
    }
}

function requireMethod($method) {
    if ($_SERVER['REQUEST_METHOD'] !== $method) {
        http_response_code(405);
        echo json_encode(['success' => false, 'message' => '方法不允许']);
        exit;
    }
}

function readDataFile($relativePath) {
    $filePath = DATA_DIR . '/' . ltrim($relativePath, '/');
    if (file_exists($filePath)) {
        $content = file_get_contents($filePath);
        $decoded = json_decode($content, true);
        return $decoded;
    }
    return null;
}

function readDataDir($subDir) {
    $dirPath = DATA_DIR . '/' . ltrim($subDir, '/');
    $items = [];
    if (is_dir($dirPath)) {
        $files = glob($dirPath . '/*.json');
        if ($files === false) { $files = []; }
        foreach ($files as $file) {
            $data = json_decode(file_get_contents($file), true);
            if ($data) {
                $items[] = $data;
            }
        }
    }
    return $items;
}

// getPageBySlug只返回具体页面的数据，不返回整个page-index.json
function getPageBySlug($slug) {
    $db = getDB();
    if ($db) {
        $safeSlug = $db->real_escape_string($slug);
        $result = $db->query("SELECT * FROM pages WHERE slug = '$safeSlug' AND status = 1 LIMIT 1");
        if ($result && $result->num_rows > 0) {
            return $result->fetch_assoc();
        }
    }
    return null;
}

function jsonSuccess($data, $extra = []) {
    $output = array_merge([
        'success' => true,
        'data' => $data,
        'timestamp' => time()
    ], $extra);
    echo json_encode($output, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    exit;
}

function jsonError($message, $code = 500) {
    http_response_code($code);
    echo json_encode([
        'success' => false,
        'message' => $message,
        'code' => $code
    ], JSON_UNESCAPED_UNICODE);
    exit;
}
