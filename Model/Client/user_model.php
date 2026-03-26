<?php

declare(strict_types=1);

function getUsername(string $username, object $conn)
{
    $query = "SELECT * FROM taikhoan WHERE USERNAME=?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $username);
    $stmt->execute();

    $result = $stmt->get_result();
    if ($row = $result->fetch_assoc()) {
        return $row;
    } else {
        return false;
    }
}


function get_Phone(object $conn, string $sdt)
{
    $query = "SELECT * FROM taikhoan WHERE SDT=?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $sdt);
    $stmt->execute();

    $result = $stmt->get_result();
    if ($row = $result->fetch_assoc()) {
        return $row;
    } else {
        return false;
    }
}

function get_Email(object $conn, string $email)
{
    $query = "SELECT * FROM taikhoan WHERE EMAIL=?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $email);
    $stmt->execute();

    $result = $stmt->get_result();
    if ($row = $result->fetch_assoc()) {
        return $row;
    } else {
        return false;
    }
}

function insert_taikhoan(object $conn, string $username, string $email, string $sdt, string $hoten, string $pwd)
{
    $query = "INSERT INTO taikhoan(USERNAME, PASSWORD, SDT, EMAIL, HOTEN, idQUYEN, TRANGTHAI)
                VALUES (?, ?, ?, ?, ?, 0, 1)";

    $stmt = $conn->prepare($query);

    $options = [
        "cost" => 12
    ];

    $hashePwd = password_hash($pwd, PASSWORD_BCRYPT, $options);

    $stmt->bind_param("sssss", $username, $hashePwd, $sdt, $email, $hoten);
    $stmt->execute();

}

function insert_fav(object $conn, int $idTK, int $idSP)
{
    $query = "INSERT INTO favourite(idTK, idSP)
                VALUES (?, ?)";

    $stmt = $conn->prepare($query);

    $stmt->bind_param("ii", $idTK, $idSP);
    $stmt->execute();

}


function delete_fav(object $conn, int $idTK, int $idSP)
{
    $query = "DELETE FROM favourite
                WHERE idTK=? AND idSP=?";

    $stmt = $conn->prepare($query);

    $stmt->bind_param("ii", $idTK, $idSP);
    $stmt->execute();

}


function updatePwd(object $conn, string $pwd, int $idTK): bool
{
    $query = "UPDATE taikhoan SET PASSWORD = ? WHERE idTK = ?";
    $stmt = $conn->prepare($query);

    if (!$stmt) {
        echo "Lỗi truy vấn: " . $conn->error;
        return false;
    }

    $options = ["cost" => 12];
    $hashedPwd = password_hash($pwd, PASSWORD_BCRYPT, $options);

    $stmt->bind_param("si", $hashedPwd, $idTK);
    $stmt->execute();

    if ($stmt->affected_rows > 0) {
        $stmt->close();
        return true; // Cập nhật thành công
    } else {
        $stmt->close();
        return false; // Không cập nhật được (có thể mật khẩu mới trùng mật khẩu cũ)
    }
}


function get_FavProducts(object $dbCon, int $idTK): array
{
    $query = "
        SELECT s.* 
        FROM favourite f
        JOIN sanpham s ON f.idSP = s.idSP
        WHERE f.idTK = ?
    ";
    
    $stmt = $dbCon->prepare($query);
    $stmt->bind_param("i", $idTK);
    $stmt->execute();

    $result = $stmt->get_result();
    $favProducts = [];

    while ($row = $result->fetch_assoc()) {
        $favProducts[] = $row;
    }

    $stmt->close();
    
    return $favProducts; // Trả về danh sách sản phẩm yêu thích
}
function remove_FavoriteProduct($db_Con, $idTKK, $productId) 
{
    $query = "DELETE FROM favourite WHERE idTK = ? AND idSP = ?";
    $stmt = $db_Con->prepare($query);
    $stmt->bind_param("ii", $idTKK, $productId);
    return $stmt->execute();
}