<?php
require_once __DIR__.'/config/database.php';
$username='admin';$password='admin123';$hash=password_hash($password,PASSWORD_DEFAULT);
$st=$pdo->prepare("INSERT INTO users(username,password,role) VALUES(?,?,?) ON DUPLICATE KEY UPDATE password=VALUES(password),role='admin'");$st->execute([$username,$hash,'admin']);
echo 'Admin account created/updated. Username: admin Password: admin123. DELETE THIS FILE after running it.';
