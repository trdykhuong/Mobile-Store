<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once("create_vnpay.php");

$payment = new PaymentController();
$payment->handleVnpayReturn();
?>
