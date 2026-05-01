<?php
/**
 * 案例详情保存API
 * 保存案例数据到JSON文件
 */

// 开启错误报告（开发环境）
error_reporting(E_ALL);
ini_set('display_errors', 0);

// 设置响应头
header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

// 错误处理函数
function handleError($errno, $errstr, $errfile, $errline) {
    $errorMsg = "Error [$errno]: $errstr in $errfile on line $errline";
    error_log($errorMsg);
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => '服务器内部错误: ' . $errstr]);
    exit;
}
set_error_handler('handleError');



// 异常处理
try {

// 处理预检请求
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

// 只允许POST请求
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => '方法不允许']);
    exit;
}

// 获取POST数据
$json = file_get_contents('php://input');
$data = json_decode($json, true);

if (!$data) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => '无效的JSON数据']);
    exit;
}

// 验证必需字段
if (empty($data['id'])) {
    debug_log('缺少案例ID');
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => '缺少案例ID']);
    exit;
}

$caseId = preg_replace('/[^a-zA-Z0-9_-]/', '', $data['id']);

// 数据目录
// __DIR__ = D:\yingyong\xampp\htdocs\hongdu\admin\api\case\
// 需要指向 D:\yingyong\xampp\htdocs\hongdu\admin\data\cases\
$dataDir = dirname(dirname(__DIR__)) . '/data/cases/';

// 确保目录存在
if (!file_exists($dataDir)) {
    mkdir($dataDir, 0755, true);
}

// 确保status字段有效
$status = isset($data['status']) && in_array($data['status'], ['draft', 'published']) ? $data['status'] : 'draft';

// 准备保存的数据
$caseData = [
    'id' => $caseId,
    'title' => isset($data['title']) ? $data['title'] : '',
    'type' => isset($data['type']) ? $data['type'] : '过桥',
    'city' => isset($data['city']) ? $data['city'] : '北京',
    'summary' => isset($data['summary']) ? $data['summary'] : '',
    'amount' => isset($data['amount']) ? $data['amount'] : '',
    'period' => isset($data['period']) ? $data['period'] : '',
    'year' => isset($data['year']) ? $data['year'] : date('Y'),
    'image' => isset($data['image']) ? $data['image'] : '',
    'coverImage' => isset($data['coverImage']) ? $data['coverImage'] : (isset($data['images']) && is_array($data['images']) && count($data['images']) > 0 ? $data['images'][0] : ''),
    'images' => isset($data['images']) && is_array($data['images']) ? $data['images'] : [],
    'hasVideo' => isset($data['hasVideo']) ? (bool)$data['hasVideo'] : false,
    'video' => isset($data['video']) ? $data['video'] : '',
    'detail' => isset($data['detail']) ? $data['detail'] : '',
    'highlights' => isset($data['highlights']) && is_array($data['highlights']) ? $data['highlights'] : [],
    'process' => isset($data['process']) && is_array($data['process']) ? $data['process'] : [],
    'status' => $status,
    'lastModified' => date('Y-m-d H:i:s')
];

// 如果没有主图但有图片列表，使用第一张作为主图
if (empty($caseData['image']) && !empty($caseData['images'])) {
    $caseData['image'] = $caseData['images'][0];
}

// 保存到文件
$dataFile = $dataDir . $caseId . '.json';
$result = file_put_contents($dataFile, json_encode($caseData, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));

if ($result === false) {
    error_log('[save.php] 保存案例数据失败: ' . $caseId);
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => '保存案例数据失败']);
    exit;
}

// 同时更新案例列表索引
updateCaseIndex($caseData);

// 同步发布到前端 - 复制一份到根目录data文件夹供前端读取
$syncResult = syncToFrontend($caseData);
if (!$syncResult) {
    error_log('[save.php] 同步到前端失败: ' . $caseId);
}

echo json_encode([
    'success' => true,
    'message' => '案例保存成功',
    'case' => $caseData
]);

} catch (Exception $e) {
    error_log('Save case error: ' . $e->getMessage());
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => '服务器错误: ' . $e->getMessage()]);
    exit;
}

/**
 * 更新案例列表索引
 */
function updateCaseIndex($caseData) {
    // 使用绝对路径确保正确
    $indexFile = dirname(dirname(__DIR__)) . '/data/cases-index.json';
    
    // 确保目录存在
    $indexDir = dirname($indexFile);
    if (!file_exists($indexDir)) {
        mkdir($indexDir, 0755, true);
    }
    
    $index = [];
    if (file_exists($indexFile)) {
        $json = file_get_contents($indexFile);
        $index = json_decode($json, true);
        if (!is_array($index)) {
            $index = [];
        }
    }
    
    // 查找是否已存在
    $found = false;
    foreach ($index as &$item) {
        if ($item['id'] === $caseData['id']) {
            $item['title'] = $caseData['title'];
            $item['type'] = $caseData['type'];
            $item['city'] = $caseData['city'];
            $item['amount'] = $caseData['amount'];
            $item['summary'] = isset($caseData['summary']) ? $caseData['summary'] : '';
            $item['image'] = $caseData['image'];
            $item['coverImage'] = isset($caseData['coverImage']) ? $caseData['coverImage'] : $caseData['image'];
            $item['images'] = isset($caseData['images']) ? $caseData['images'] : [];
            $item['status'] = isset($caseData['status']) ? $caseData['status'] : 'draft';
            $item['lastModified'] = $caseData['lastModified'];
            $found = true;
            break;
        }
    }
    
    // 如果不存在，添加新条目
    if (!$found) {
        $index[] = [
            'id' => $caseData['id'],
            'title' => $caseData['title'],
            'type' => $caseData['type'],
            'city' => $caseData['city'],
            'amount' => $caseData['amount'],
            'summary' => isset($caseData['summary']) ? $caseData['summary'] : '',
            'image' => $caseData['image'],
            'coverImage' => isset($caseData['coverImage']) ? $caseData['coverImage'] : $caseData['image'],
            'images' => isset($caseData['images']) ? $caseData['images'] : [],
            'status' => isset($caseData['status']) ? $caseData['status'] : 'draft',
            'lastModified' => $caseData['lastModified']
        ];
    }
    
    file_put_contents($indexFile, json_encode($index, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
}

/**
 * 同步案例到前端目录
 * 将发布的案例复制到根目录的data文件夹供前端读取
 * @return bool 同步是否成功
 */
function syncToFrontend($caseData) {
    // 前端数据目录 - 根目录下的data文件夹
    // __DIR__ = D:\yingyong\xampp\htdocs\hongdu\admin\api\case\
    // dirname(__DIR__) = D:\yingyong\xampp\htdocs\hongdu\admin\api\
    // dirname(dirname(__DIR__)) = D:\yingyong\xampp\htdocs\hongdu\admin\
    // dirname(dirname(dirname(__DIR__))) = D:\yingyong\xampp\htdocs\hongdu\
    // 需要指向 D:\yingyong\xampp\htdocs\hongdu\data\
    $frontendDataDir = dirname(dirname(dirname(__DIR__))) . '/data/cases/';
    $frontendIndexFile = dirname(dirname(dirname(__DIR__))) . '/data/cases-index.json';
    
    // 确保前端目录存在
    if (!file_exists($frontendDataDir)) {
        if (!mkdir($frontendDataDir, 0755, true)) {
            error_log('创建前端数据目录失败: ' . $frontendDataDir);
            return false;
        }
    }
    
    // 只同步已发布的案例到前端
    $status = isset($caseData['status']) ? $caseData['status'] : 'draft';
    if ($status === 'published') {
        // 保存案例详情到前端目录
        $frontendCaseFile = $frontendDataDir . $caseData['id'] . '.json';
        $result = file_put_contents($frontendCaseFile, json_encode($caseData, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
        if ($result === false) {
            error_log('保存案例到前端目录失败: ' . $frontendCaseFile);
            return false;
        }
        
        // 更新前端索引
        $frontendIndex = [];
        if (file_exists($frontendIndexFile)) {
            $json = file_get_contents($frontendIndexFile);
            $frontendIndex = json_decode($json, true);
            if (!is_array($frontendIndex)) {
                $frontendIndex = [];
            }
        } else {
            // 确保索引文件目录存在
            $frontendIndexDir = dirname($frontendIndexFile);
            if (!file_exists($frontendIndexDir)) {
                if (!mkdir($frontendIndexDir, 0755, true)) {
                    error_log('创建前端索引目录失败: ' . $frontendIndexDir);
                    return false;
                }
            }
        }
        
        // 查找或添加案例到前端索引
        $found = false;
        foreach ($frontendIndex as &$item) {
            if ($item['id'] === $caseData['id']) {
                $item['title'] = $caseData['title'];
                $item['type'] = $caseData['type'];
                $item['city'] = $caseData['city'];
                $item['amount'] = $caseData['amount'];
                $item['summary'] = isset($caseData['summary']) ? $caseData['summary'] : '';
                $item['image'] = $caseData['image'];
                $item['coverImage'] = isset($caseData['coverImage']) ? $caseData['coverImage'] : $caseData['image'];
                $item['images'] = isset($caseData['images']) ? $caseData['images'] : [];
                $item['status'] = isset($caseData['status']) ? $caseData['status'] : 'draft';
                $item['lastModified'] = $caseData['lastModified'];
                $found = true;
                break;
            }
        }
        
        if (!$found) {
            $frontendIndex[] = [
                'id' => $caseData['id'],
                'title' => $caseData['title'],
                'type' => $caseData['type'],
                'city' => $caseData['city'],
                'amount' => $caseData['amount'],
                'summary' => isset($caseData['summary']) ? $caseData['summary'] : '',
                'image' => $caseData['image'],
                'coverImage' => isset($caseData['coverImage']) ? $caseData['coverImage'] : $caseData['image'],
                'images' => isset($caseData['images']) ? $caseData['images'] : [],
                'status' => isset($caseData['status']) ? $caseData['status'] : 'draft',
                'lastModified' => $caseData['lastModified']
            ];
        }
        
        $indexResult = file_put_contents($frontendIndexFile, json_encode($frontendIndex, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
        if ($indexResult === false) {
            error_log('保存前端索引文件失败: ' . $frontendIndexFile);
            return false;
        }
        
        return true;
    } else {
        // 如果是草稿状态，从前端目录删除（如果存在）
        $frontendCaseFile = $frontendDataDir . $caseData['id'] . '.json';
        if (file_exists($frontendCaseFile)) {
            unlink($frontendCaseFile);
        }
        
        // 从前端索引中移除
        if (file_exists($frontendIndexFile)) {
            $json = file_get_contents($frontendIndexFile);
            $frontendIndex = json_decode($json, true);
            if (is_array($frontendIndex)) {
                $frontendIndex = array_filter($frontendIndex, function($item) use ($caseData) {
                    return $item['id'] !== $caseData['id'];
                });
                file_put_contents($frontendIndexFile, json_encode(array_values($frontendIndex), JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
            }
        }
        
        return true;
    }
}
