<?php
if (session_status() !== PHP_SESSION_ACTIVE) session_start();
require_once __DIR__ . '/config/database.php';

function e($value) { return htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8'); }
function money($value) { return '₱' . number_format((float)$value, 2); }
function cart_count() { return array_sum($_SESSION['cart'] ?? []); }
function get_products(PDO $pdo) {
    $stmt = $pdo->query("SELECT * FROM products WHERE status='active' ORDER BY product_id");
    return $stmt->fetchAll();
}
function get_product(PDO $pdo, int $id) {
    $stmt = $pdo->prepare("SELECT * FROM products WHERE product_id=? AND status='active'");
    $stmt->execute([$id]); return $stmt->fetch();
}
function flash($type, $message) { $_SESSION['flash'] = ['type'=>$type,'message'=>$message]; }
function get_flash() { $f=$_SESSION['flash']??null; unset($_SESSION['flash']); return $f; }
function require_admin() { if (empty($_SESSION['admin_user'])) { header('Location: login.php'); exit; } }
function order_reference() { return 'R5-' . strtoupper(substr(bin2hex(random_bytes(5)), 0, 8)); }
