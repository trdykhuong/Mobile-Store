<?php
session_start();

require_once "../Model/db_connect.php";
require_once "../Model/Client/user_model.php";

if (!isset($_SESSION["id"])) {
    echo "error";
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $idSP = $_POST["idSP"];
    $action = $_POST["action"];
    
    $conn = new Database();
    $conn->connect();
    
    if ($action == "add") {
        insert_fav($conn, $_SESSION["id"], $idSP);
    } else {
        delete_fav($conn, $_SESSION["id"], $idSP);
    }

    echo "success";
    exit();
}
?>
