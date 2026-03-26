<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once '../../Model/cart/CartModel.php';

$idSP = $_GET['idSP'] ?? null;
$idCTSP = $_GET['idCTSP'] ?? null;
$idTK = $_SESSION['id'] ?? null;

if (!$idSP || !$idTK || !$idCTSP) {
    echo json_encode(['in_cart' => false]);
    exit();
}

$cartModel = new CartModel();
$currentQuantity = $cartModel->getCartItemQuantity($idTK, $idCTSP);

echo json_encode(['in_cart' => $currentQuantity > 0]);
?>
