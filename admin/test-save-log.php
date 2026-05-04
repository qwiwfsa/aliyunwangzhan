<?php
/**
 * 测试保存并显示日志
 */

require_once __DIR__ . '/api/config.php';

header('Content-Type: text/html; charset=utf-8');

// 读取最近的保存请求日志
$logFile = __DIR__ . '/save-debug.log';

if (file_exists($logFile)) {
    echo "<h1>最近的保存请求日志</h1>";
    echo "<pre>" . htmlspecialchars(file_get_contents($logFile)) . "</pre>";
    echo "<hr>";
    echo "<button onclick=\"location.reload()\">刷新</button>";
    echo "<button onclick=\"clearLog()\">清除日志</button>";
    echo "<script>
    function clearLog() {
        fetch('clear-log.php').then(() => location.reload());
    }
    </script>";
} else {
    echo "<h1>还没有保存日志</h1>";
    echo "<p>请在可视化编辑器中修改内容并保存，然后刷新此页面查看日志。</p>";
}
?>
