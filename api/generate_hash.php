<?php
if ($argc != 2) {
    echo "使用方法: php generate_hash.php <密码>\n";
    exit(1);
}

$password = $argv[1];
$hash = password_hash($password, PASSWORD_DEFAULT);

echo "密码: $password\n";
echo "哈希: $hash\n";