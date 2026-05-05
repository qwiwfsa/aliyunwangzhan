<?php
/**
 * 数据库配置文件 - 自动适配本地和服务器环境
 */

// 数据库配置 - 根据环境自动切换
if(in_array($_SERVER['SERVER_ADDR'] ?? '', ['127.0.0.1', '::1']) || ($_SERVER['HTTP_HOST'] ?? '') == 'localhost') {
    // 本地环境 (XAMPP)
    define('DB_HOST', 'localhost');
    define('DB_NAME', 'hongdu');
    define('DB_USER', 'root');
    define('DB_PASS', '');
} else {
    // 服务器环境 (阿里云宝塔)
    define('DB_HOST', '127.0.0.1');
    define('DB_NAME', 'hongdu');
    define('DB_USER', 'root');
    define('DB_PASS', '');
}
define('DB_CHARSET', 'utf8mb4');

// 创建数据库连接
function getDB() {
    static $pdo = null;
    
    if ($pdo === null) {
        try {
            $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=" . DB_CHARSET;
            $pdo = new PDO($dsn, DB_USER, DB_PASS);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            http_response_code(500);
            echo json_encode(['error' => '数据库连接失败: ' . $e->getMessage()]);
            exit;
        }
    }
    
    return $pdo;
}

// 设置响应头
header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

// 处理OPTIONS请求
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

// 获取JSON输入
function getInput() {
    $json = file_get_contents('php://input');
    return json_decode($json, true) ?: [];
}

// 统一响应格式
function response($data, $code = 200) {
    http_response_code($code);
    echo json_encode([
        'code' => $code,
        'data' => $data,
        'message' => $code === 200 ? 'success' : 'error'
    ]);
    exit;
}

// 错误响应
function error($message, $code = 400) {
    http_response_code($code);
    echo json_encode([
        'code' => $code,
        'error' => $message
    ]);
    exit;
}
