<?php

declare(strict_types= 1);

function is_input_empty(array &$errors, string $username, string $pwd, string $hoten, string $sdt, string $email) {

    if (empty($username)) {
        $errors["username_empty"] = "Vui lòng nhập tên đăng nhập!";
    }
    
    // Kiểm tra số điện thoại
    if (empty($sdt)) {
        $errors["sdt_empty"] = "Vui lòng nhập số điện thoại!";
    }
    
    // Kiểm tra email
    if (empty($email)) {
        $errors["email_empty"] = "Vui lòng nhập email!";
    }
    
    // Kiểm tra họ tên
    if (empty($hoten)) {
        $errors["hoten_empty"] = "Vui lòng nhập họ tên!";
    }

    //Kiểm tra pwd
    if (empty($pwd)) {
        $errors["pwd_empty"] = "Vui lòng nhập mật khẩu!";
    }
    
}


function isValidPhoneNumber(string $phone): bool {
    // Biểu thức chính quy kiểm tra số điện thoại
    return preg_match('/^0[0-9]{9}$/', $phone) === 1;
}

function is_phone_invalid(string $phone) {
    if(!isValidPhoneNumber($phone)) {
        return true;
    } else {
        return false;
    }
}

function is_taken(bool|array $result) {
    if($result) {
        return true;
    } else {
        return false;
    }
}

function is_email_invalid(string $email): bool {
    if(filter_var($email, FILTER_VALIDATE_EMAIL)) {
        return false;
    } else {
        return true;
    }
}


function isValidPass(string $pwd): bool {
    // Biểu thức chính quy kiểm tra pass
    $pattern = "/^(?=.*[A-Za-z])(?=.*\d)[A-Za-z\d]{6,}$/";
    return preg_match($pattern, $pwd) === 1;
}


function is_pwd_invalid(string $pwd) {
    if(isValidPass($pwd)) {
        return false;
    } else {
        return true;
    }
}

function is_check_pwd(string $pwd, string $pwd2) {
    if($pwd === $pwd2) {
        return false;
    } else {
        return true;
    }
}


function create_taikhoan(object $conn, string $username, string $email, string $sdt, string $hoten, string $pwd) {
    insert_taikhoan($conn, $username, $email, $sdt, $hoten, $pwd);
}


