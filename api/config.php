<?php
// 数据文件路径
const DATA_FILE = __DIR__ . '/../data/uploads.json';

// 管理员密码 (使用password_hash('newpassword123', PASSWORD_DEFAULT)生成)
const ADMIN_PASSWORD_HASH = '$2y$12$Mpo/L6vKxp5wX3WnLqmu2eJHbj3cFlJTbq2BHsDQNo2EEmX3RkMSW';

// 允许的资源类型
const ALLOWED_TYPES = ['document', 'video', 'archive', 'program', 'image', 'game'];

// CORS headers
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');
header('Content-Type: application/json; charset=utf-8');

// 处理OPTIONS请求
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

/**
 * 读取数据文件
 */
function readData()
{
    if (!file_exists(DATA_FILE)) {
        return [];
    }

    $json = file_get_contents(DATA_FILE);
    return json_decode($json, true) ?: [];
}

/**
 * 写入数据文件
 */
function writeData($data)
{
    $json = json_encode($data, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
    return file_put_contents(DATA_FILE, $json) !== false;
}

/**
 * 生成唯一ID
 */
function generateId()
{
    return bin2hex(random_bytes(6));
}

/**
 * 验证管理员权限
 */
function verifyAdmin()
{
    $headers = getallheaders();
    if (!isset($headers['Authorization'])) {
        http_response_code(401);
        echo json_encode(['error' => '未提供授权信息']);
        exit();
    }

    $authHeader = $headers['Authorization'];
    if (strpos($authHeader, 'Bearer ') !== 0) {
        http_response_code(401);
        echo json_encode(['error' => '授权格式错误']);
        exit();
    }

    $token = substr($authHeader, 7);
    if (!password_verify($token, ADMIN_PASSWORD_HASH)) {
        http_response_code(401);
        echo json_encode(['error' => '密码错误']);
        exit();
    }
}
