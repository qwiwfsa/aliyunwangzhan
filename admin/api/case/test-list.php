<?php
/**
 * 测试案例列表API
 */

error_reporting(E_ALL);
ini_set('display_errors', 1);

header('Content-Type: text/plain; charset=utf-8');

$adminDir = dirname(dirname(__DIR__));
$dataDir = $adminDir . '/data/cases/';
$indexFile = $adminDir . '/data/cases-index.json';

echo "=== 调试信息 ===\n\n";

echo "数据目录: " . $dataDir . "\n";
echo "索引文件: " . $indexFile . "\n\n";

echo "数据目录是否存在: " . (file_exists($dataDir) ? '是' : '否') . "\n";
echo "索引文件是否存在: " . (file_exists($indexFile) ? '是' : '否') . "\n\n";

if (file_exists($indexFile)) {
    $json = file_get_contents($indexFile);
    $cases = json_decode($json, true);
    
    echo "索引中的案例总数: " . count($cases) . "\n\n";
    
    echo "=== 索引中的案例 ===\n";
    foreach ($cases as $case) {
        echo "- ID: " . $case['id'] . ", 标题: " . $case['title'] . "\n";
    }
    echo "\n";
    
    // 验证文件是否存在
    echo "=== 验证案例文件 ===\n";
    $validCases = [];
    foreach ($cases as $case) {
        $caseFile = $dataDir . $case['id'] . '.json';
        $exists = file_exists($caseFile);
        echo "[" . ($exists ? "OK" : "MISSING") . "] " . $case['id'] . " -> " . $caseFile . "\n";
        if ($exists) {
            $validCases[] = $case;
        }
    }
    
    echo "\n有效案例数: " . count($validCases) . "\n";
}

echo "\n=== 实际目录中的文件 ===\n";
if (file_exists($dataDir)) {
    $files = glob($dataDir . '*.json');
    foreach ($files as $file) {
        echo "- " . basename($file) . "\n";
    }
    echo "\n目录中文件总数: " . count($files) . "\n";
}
