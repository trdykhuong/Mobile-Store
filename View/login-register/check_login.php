<?php
    session_start();

    $response = ["logged_in" => isset($_SESSION['username'])];

    header('Content-Type: application/json');
    echo json_encode($response);
?>