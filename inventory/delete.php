<?php
/**
 * inventory/delete.php — Envanter kalemi silme (DELETE)
 */
require_once __DIR__ . '/../includes/auth_guard.php';
require_once __DIR__ . '/../classes/Inventory.php';
require_once __DIR__ . '/../includes/functions.php';

// Yalnızca POST kabul et
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: index.php');
    exit;
}

$userId = $_SESSION['user_id'];
$id = isset($_POST['id']) ? intval($_POST['id']) : 0;

$inventory = new Inventory();
$result = $inventory->delete($id, $userId);

if ($result) {
    setFlash('success', 'Envanter kalemi başarıyla silindi.');
} else {
    setFlash('error', 'Envanter silinemedi veya erişim yetkiniz yok.');
}

header('Location: index.php');
exit;
