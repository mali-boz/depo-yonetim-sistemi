<?php
/**
 * shipments/delete.php — Sevkiyat silme (DELETE)
 */
require_once __DIR__ . '/../includes/auth_guard.php';
require_once __DIR__ . '/../classes/Shipment.php';
require_once __DIR__ . '/../includes/functions.php';

// Yalnızca POST kabul et
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: index.php');
    exit;
}

$userId = $_SESSION['user_id'];
$id = isset($_POST['id']) ? intval($_POST['id']) : 0;

$shipment = new Shipment();
$result = $shipment->delete($id, $userId);

if ($result) {
    setFlash('success', 'Sevkiyat başarıyla silindi.');
} else {
    setFlash('error', 'Sevkiyat silinemedi veya erişim yetkiniz yok.');
}

header('Location: index.php');
exit;
