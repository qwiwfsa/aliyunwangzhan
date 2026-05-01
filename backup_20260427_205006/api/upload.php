<?php
/**
 * 文件上传API
 */

require_once 'config.php';

// 上传目录
$uploadDir = __DIR__ . '/../uploads/';

// 确保上传目录存在
if (!is_dir($uploadDir)) {
    mkdir($uploadDir, 0755, true);
}

$method = $_SERVER['REQUEST_METHOD'];
$db = getDB();

if ($method !== 'POST') {
    error('只支持POST请求', 405);
}

// 检查是否有文件上传
if (!isset($_FILES['file'])) {
    error('没有上传文件');
}

$file = $_FILES['file'];

// 检查上传错误
if ($file['error'] !== UPLOAD_ERR_OK) {
    error('上传失败: ' . $file['error']);
}

// 检查文件大小 (最大10MB)
$maxSize = 10 * 1024 * 1024;
if ($file['size'] > $maxSize) {
    error('文件太大，最大支持10MB');
}

// 允许的文件类型
$allowedTypes = [
    'image/jpeg' => 'jpg',
    'image/png' => 'png',
    'image/gif' => 'gif',
    'image/webp' => 'webp',
    'application/pdf' => 'pdf',
    'video/mp4' => 'mp4'
];

$fileType = $file['type'];
if (!isset($allowedTypes[$fileType])) {
    error('不支持的文件类型: ' . $fileType);
}

// 生成唯一文件名
$extension = $allowedTypes[$fileType];
$filename = uniqid() . '_' . time() . '.' . $extension;
$filepath = $uploadDir . $filename;

// 移动上传的文件
if (move_uploaded_file($file['tmp_name'], $filepath)) {
    // 保存到数据库
    $relativePath = 'uploads/' . $filename;
    
    try {
        $stmt = $db->prepare("INSERT INTO media (filename, original_name, file_path, file_type, file_size) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([
            $filename,
            $file['name'],
            $relativePath,
            $fileType,
            $file['size']
        ]);
        
        $id = $db->lastInsertId();
        
        response([
            'id' => $id,
            'filename' => $filename,
            'original_name' => $file['name'],
            'url' => $relativePath,
            'size' => $file['size']
        ]);
    } catch (PDOException $e) {
        error('保存到数据库失败: ' . $e->getMessage());
    }
} else {
    error('文件保存失败');
}
