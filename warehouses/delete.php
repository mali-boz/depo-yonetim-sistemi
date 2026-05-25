<?php
/**
 * warehouses/delete.php — Depo silme (DELETE)
 */
require_once __DIR__ . '/../includes/auth_guard.php';
require_once __DIR__ . '/../classes/Warehouse.php';
require_once __DIR__ . '/../includes/functions.php';

// Yalnızca POST kabul et
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: index.php');
    exit;
}

$userId = $_SESSION['user_id'];
$id = isset($_POST['id']) ? intval($_POST['id']) : 0;

$warehouse = new Warehouse();
$result = $warehouse->delete($id, $userId);

if ($result) {
    setFlash('success', 'Depo başarıyla silindi.');
} else {
    setFlash('error', 'Depo silinemedi. Bu depoya bağlı sevkiyat veya envanter kayıtları olabilir.');
}

header('Location: index.php');
exit;
