<?php
require_once 'D:\yingyong\xampp\htdocs\hongdu\config\db.php';

// 测试1: 数据库连接
try {
    $conn = getDB();
    $result = $conn->query('SELECT COUNT(*) as cnt FROM footer_settings');
    $row = $result->fetch_assoc();
    echo "数据库记录数: " . $row['cnt'] . "\n";
    $conn->close();
} catch (Exception $e) {
    echo "数据库错误: " . $e->getMessage() . "\n";
}

// 测试2: footer.php输出
try {
    ob_start();
    include 'D:\yingyong\xampp\htdocs\hongdu\includes\footer.php';
    $html = ob_get_clean();
    echo "\n=== footer.php 输出 ===\n";
    echo "输出长度: " . strlen($html) . " 字节\n";
    if (strlen($html) > 100) {
        echo "前200字符: " . substr($html, 0, 200) . "\n\n";
        echo "后200字符: " . substr($html, -200) . "\n";
    } else {
        echo "全部内容: " . $html . "\n";
    }
} catch (Exception $e) {
    echo "footer.php错误: " . $e->getMessage() . "\n";
}
