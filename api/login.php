<?php
require_once 'config.php';

// 管理员登录
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // 获取POST数据
    // 支持JSON和FormData两种格式
    $input = json_decode(file_get_contents('php://input'), true);
    if ($input === null) {
        // 如果JSON解析失败，尝试从$_POST获取数据（FormData格式）
        $input = $_POST;
    }
    
    // 验证必需字段
    if (!isset($input['password'])) {
        http_response_code(400);
        echo json_encode(['error' => '缺少密码字段']);
        exit();
    }
    
    // 验证密码
    if (password_verify($input['password'], ADMIN_PASSWORD_HASH)) {
        // 生成token (简单实现，实际应用中应使用JWT等更安全的方式)
        $token = $input['password'];
        
        echo json_encode([
            'success' => true,
            'message' => '登录成功',
            'token' => $token
        ]);
    } else {
        http_response_code(401);
        echo json_encode(['error' => '密码错误']);
    }
    exit();
}

http_response_code(405);
echo json_encode(['error' => '方法不允许']);