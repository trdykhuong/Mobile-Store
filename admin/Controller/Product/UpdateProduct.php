<?php
$conn=mysqli_connect("localhost:3306", "root", "", "chdidong");
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $json = file_get_contents('php://input');
    $data = json_decode($json, true);

    $idctsp = isset($data['idctsp'])? intval($data['idctsp']) : 0;
    $hang = isset($data['hang'])? intval($data['hang']) : 1;
    $danhmuc = isset($data['danhmuc'])? intval($data['danhmuc']) : 1;
    $mausac = isset($data['mausac'])? $data['mausac'] : 'KHÔNG CÓ';
    $dungluong = isset($data['dungluong'])? $data['dungluong'] : 'KHÔNG CÓ';
    $mota = isset($data['mota'])? $data['mota'] : "Hiện không có mô tả sản phẩm.";
    $giamgia = isset($data['giamgia'])? $data['giamgia'] : 0;

    $message='';
    $message_img='123';
    $message2 = '12345';

    //Xử lý file ảnh
    $img_name = isset($data['fileName'])? basename($data['fileName']) : ''; //Lấy ra tên file gốc
    $ext_img = pathinfo($img_name, PATHINFO_EXTENSION);//Lấy đuôi file
    $img_data = isset($data['fileData'])? $data['fileData'] : '';  //Dữ liệu file
    //Ấy nó ra
    $img_content = base64_decode($img_data);

    //Lấy ra tên và url image cũ
    $sql_sp = mysqli_query($conn, "SELECT idSP, IMG from chitietsanpham WHERE idCTSP=" . intval($idctsp));
    $result_id = mysqli_fetch_assoc($sql_sp);


    $sql = mysqli_query($conn, "SELECT TENSP from sanpham WHERE idSP=" . intval($result_id['idSP']));
    $result = mysqli_fetch_assoc($sql);

    if (!$result_id || !$result) {
        echo json_encode(['message' => 'Không tìm thấy thông tin sản phẩm']);
        exit;
    }
    
    $temp_nameIMG = "";
    if($mausac != "KHÔNG CÓ")
    $temp_nameIMG .= "_" . $mausac;

    if($dungluong != "KHÔNG CÓ")
    $temp_nameIMG .= "_" . $dungluong;

    $name = $result['TENSP'] . $temp_nameIMG;
    $img_old = $result_id['IMG'];
    //////
    $new_url = $name . "." . $ext_img; //Như cái tên
    $images_dir = $_SERVER["DOCUMENT_ROOT"] . "/images/products"; //folder ảnh

    if(strlen($ext_img) > 0){
        //Xóa file ảnh cũ
        $del_path = $images_dir.'/'.$img_old;
        if (file_exists($del_path)) {
            unlink($del_path);
        }
        
        //Update hình mới
        $query = "UPDATE chitietsanpham SET IMG='$new_url' WHERE idCTSP =". intval($idctsp);
        mysqli_query($conn, $query); 
        // //Đẩy dữ liệu file ảnh vào
        $images_dir_img = $images_dir.'/'.$new_url;
        $message_img = file_put_contents($images_dir_img, $img_content)? "Tải ảnh dc r" : "Lỗi r ku";
    }
    
    $sql2 = "UPDATE sanpham SET HANG='$hang', idDM='$danhmuc', MOTA='$mota', 
    DISCOUNT='$giamgia' WHERE idSP =" . intval($result_id['idSP']);

    $message = mysqli_query($conn, $sql2)? ("Đã cập nhật thành công sản phẩm " . $name) : "Truy vấn thất bại";

    $response = [
        'message' => $message,
        'name' => $name,
        'imgmes' => $message_img,
        'newurl' => $new_url
    ];

    $json = json_encode($response);
    echo $json;
}
?>