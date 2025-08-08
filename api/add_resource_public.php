<?php
require_once 'config.php';

// 添加新资源（公开上传版本，无需管理员权限）
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // 获取POST数据
    $input = json_decode(file_get_contents('php://input'), true);

    // 验证必需字段
    if (!isset($input['name']) || !isset($input['type']) || !isset($input['uploader']) || 
        !isset($input['date']) || !isset($input['link'])) {
        http_response_code(400);
        echo json_encode(['error' => '缺少必需字段']);
        exit();
    }

    // 验证资源类型
    if (!in_array($input['type'], ALLOWED_TYPES)) {
        http_response_code(400);
        echo json_encode(['error' => '无效的资源类型']);
        exit();
    }

    // 读取现有数据
    $resources = readData();

    // 创建新资源
    $newResource = [
        'id' => generateId(),
        'name' => trim($input['name']),
        'type' => $input['type'],
        'uploader' => trim($input['uploader']),
        'date' => $input['date'],
        'link' => trim($input['link']),
        'description' => isset($input['description']) ? trim($input['description']) : '',
        'approved' => false  // 公开上传的资源默认未审核
    ];

    // 添加到资源列表
    $resources[] = $newResource;

    // 保存数据
    if (writeData($resources)) {
        echo json_encode([
            'success' => true,
            'message' => '资源添加成功',
            'data' => $newResource
        ]);
    } else {
        http_response_code(500);
        echo json_encode(['error' => '保存数据失败']);
    }
    exit();
}

http_response_code(405);
echo json_encode(['error' => '方法不允许']);