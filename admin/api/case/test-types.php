<?php
/**
 * 业务类型API测试脚本
 */

require_once __DIR__ . '/../config.php';

header('Content-Type: text/html; charset=utf-8');

echo "<h1>业务类型API测试</h1>";

try {
    $conn = getDbConnection();
    echo "<p style='color:green'>✓ 数据库连接成功</p>";
    
    // 检查表是否存在
    $result = $conn->query("SHOW TABLES LIKE 'cms_case_types'");
    if ($result->num_rows > 0) {
        echo "<p style='color:green'>✓ 表 cms_case_types 存在</p>";
        
        // 检查表结构
        $result = $conn->query("DESCRIBE cms_case_types");
        echo "<h3>表结构:</h3><ul>";
        while ($row = $result->fetch_assoc()) {
            echo "<li>{$row['Field']} - {$row['Type']}</li>";
        }
        echo "</ul>";
        
        // 检查数据
        $result = $conn->query("SELECT * FROM cms_case_types ORDER BY sort_order ASC");
        echo "<h3>当前数据 ({$result->num_rows} 条):</h3>";
        if ($result->num_rows > 0) {
            echo "<table border='1' cellpadding='5'><tr><th>ID</th><th>名称</th><th>描述</th><th>颜色</th><th>排序</th></tr>";
            while ($row = $result->fetch_assoc()) {
                echo "<tr>";
                echo "<td>{$row['id']}</td>";
                echo "<td>{$row['name']}</td>";
                echo "<td>{$row['description']}</td>";
                echo "<td style='background:{$row['color']}'>{$row['color']}</td>";
                echo "<td>{$row['sort_order']}</td>";
                echo "</tr>";
            }
            echo "</table>";
        } else {
            echo "<p>暂无数据</p>";
        }
    } else {
        echo "<p style='color:red'>✗ 表 cms_case_types 不存在</p>";
    }
    
    $conn->close();
    
} catch (Exception $e) {
    echo "<p style='color:red'>✗ 错误: " . $e->getMessage() . "</p>";
}

echo "<hr>";
echo "<h2>API端点测试</h2>";
echo "<ul>";
echo "<li><a href='types.php' target='_blank'>GET types.php - 获取列表</a></li>";
echo "<li>POST types.php - 创建类型</li>";
echo "<li>PUT types.php - 更新类型</li>";
echo "<li>DELETE types.php - 删除类型</li>";
echo "<li>POST types-sort.php - 排序</li>";
echo "</ul>";

echo "<hr>";
echo "<h2>错误日志</h2>";
$logFile = __DIR__ . '/../logs/api-error.log';
if (file_exists($logFile)) {
    $logs = file_get_contents($logFile);
    if (empty($logs)) {
        echo "<p>暂无错误日志</p>";
    } else {
        echo "<pre style='background:#f5f5f5;padding:10px;'>" . htmlspecialchars($logs) . "</pre>";
    }
} else {
    echo "<p>日志文件不存在</p>";
}
