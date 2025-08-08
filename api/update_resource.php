<?php
require_once 'config.php';

// 更新资源
if ($_SERVER['REQUEST_METHOD'] === 'PUT') {
    // 验证管理员权限
    verifyAdmin();
    
    // 获取资源ID
    $id = $_GET['id'] ?? '';
    
    if (empty($id)) {
        http_response_code(400);
        echo json_encode(['error' => '缺少资源ID']);
        exit();
    }
    
    // 获取PUT数据
    $input = json_decode(file_get_contents('php://input'), true);
    
    // 验证必需字段
    if (!isset($input['name']) || !isset($input['type']) || !isset($input['uploader']) || !isset($input['date']) || !isset($input['link'])) {
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
    
    // 查找并更新资源
    $found = false;
    foreach ($resources as $key => $resource) {
        if ($resource['id'] === $id) {
            $resources[$key] = [
                'id' => $id,
                'name' => trim($input['name']),
                'type' => $input['type'],
                'uploader' => trim($input['uploader']),
                'date' => $input['date'],
                'link' => trim($input['link']),
                'description' => isset($input['description']) ? trim($input['description']) : ''
            ];
            $found = true;
            break;
        }
    }
    
    if (!$found) {
        http_response_code(404);
        echo json_encode(['error' => '资源未找到']);
        exit();
    }
    
    // 保存数据
    if (writeData($resources)) {
        echo json_encode([
            'success' => true,
            'message' => '资源更新成功',
            'data' => $resources[$key]
        ]);
    } else {
        http_response_code(500);
        echo json_encode(['error' => '保存数据失败']);
    }
    exit();
}

http_response_code(405);
echo json_encode(['error' => '方法不允许']);