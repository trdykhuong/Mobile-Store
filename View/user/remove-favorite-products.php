<?php
require_once "../../Model/db_connect.php";
require_once "../../Model/Client/user_model.php";

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $data = json_decode(file_get_contents("php://input"), true);
    $productIds = $data['productIds'];
    $idTKK = $_SESSION["id"];

    $db_Con = new Database();
    $db_Con->connect();

    $success = true;
    foreach ($productIds as $productId) {
        if (!remove_FavoriteProduct($db_Con, $idTKK, $productId)) {
            $success = false;
            break;
        }
    }

    $db_Con->close();

    echo json_encode(["success" => $success]);
} else {
    echo json_encode(["success" => false, "message" => "Invalid request method"]);
}
?>