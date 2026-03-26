<?php

declare(strict_types=1);


function is_input_empty(array &$errors, string $sdt, string $pwd) {
    if(empty($sdt)) {
        $errors["sdt_empty"] = "Vui lòng nhập số điện thoại!";
    } 
    if(empty($pwd)) {
        $errors["pwd_empty"] = "Vui lòng nhập mật khẩu!";
    } 
}

function isValidPhoneNumber(string $phone): bool {
    // Biểu thức chính quy kiểm tra số điện thoại
    return preg_match('/^0[0-9]{9}$/', $phone) === 1;
}

function is_phone_wrong(bool|array $result) {
    if(!$result) {
        return true;
    } else {
        return false;
    }
}

function is_pwd_wrong(string $pwd, string $hashPwd) {
    if(!password_verify($pwd, $hashPwd)) {
        return true;
    } else {
        return false;
    }
}
