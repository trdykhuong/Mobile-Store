<?php
$conn=mysqli_connect("localhost:3306", "root", "", "chdidong");
if($conn->connect_error)
{
    die("connect error: " . $conn->connect_error);
}
?>