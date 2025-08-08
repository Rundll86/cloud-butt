<?php
require_once 'config.php';

// 管理员获取所有资源列表（包括未审核的）
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    // 验证管理员权限
    verifyAdmin();
    
    $resources = readData();
    
    // 按上传日期倒序排列
    usort($resources, function($a, $b) {
        return strtotime($b['date']) - strtotime($a['date']);
    });
    
    echo json_encode([
        'success' => true,
        'data' => $resources,
        'count' => count($resources)
    ]);
    exit();
}

http_response_code(405);
echo json_encode(['error' => '方法不允许']);