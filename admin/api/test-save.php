<?php
/**
 * 测试文章保存API
 */

header('Content-Type: application/json; charset=utf-8');

// 测试数据库连接
require_once __DIR__ . '/config.php';

try {
    $conn = getDbConnection();
    
    // 初始化数据库
    initDatabase($conn);
    
    // 检查表是否存在
    $result = $conn->query("SHOW TABLES LIKE 'cms_articles'");
    $tableExists = $result->num_rows > 0;
    
    // 检查表结构
    $columns = [];
    if ($tableExists) {
        $result = $conn->query("DESCRIBE cms_articles");
        while ($row = $result->fetch_assoc()) {
            $columns[] = $row['Field'] . ' (' . $row['Type'] . ')';
        }
    }
    
    $conn->close();
    
    echo json_encode([
        'success' => true,
        'message' => '数据库连接正常',
        'data' => [
            'database_connected' => true,
            'cms_articles_table_exists' => $tableExists,
            'columns' => $columns
        ]
    ]);
} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'message' => '数据库连接失败: ' . $e->getMessage()
    ]);
}
