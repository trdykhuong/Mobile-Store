<?php
$path = $_SERVER["DOCUMENT_ROOT"] . '/admin/Controller/connectDB.php';
include($path);
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $json = file_get_contents('php://input');
    $data = json_decode($json, true);

    

    $response = [
        'tensp' => $tensp,
        'message' => $message
    ];

    $json = json_encode($response);
    echo $json;
}

  $tensp = '123';
  $hang = 1;
  $danhmuc = 1;
  $mota = "Hiện không có mô tả sản phẩm.";
  $message='';

  //Xử lý file ảnh
  $img = isset($_FILES['img']['name'])? basename($_FILES["img"]["name"]) : '123'; //basename lấy ra tên file gốc

  $imgtest = basename($_FILES["img"]["name"]);
  //Lấy đuôi file
  $ext_img = pathinfo($img, PATHINFO_EXTENSION);
  $new_url = $tensp . "." . $ext_img; //Như cái tên
  $img_temp = $_FILES['img']['tmpname']; //url của file ảnh đã tải lên
  $images_dir = $_SERVER["DOCUMENT_ROOT"] . "\images\products"; //url folder lưu ảnh

  //Tính id của hiện tại của sản phẩm
  $id_temp = mysqli_query($conn, "SELECT idSP FROM sanpham");
  $num_rows_id = mysqli_num_rows($id_temp) + 1;

  // Kiểm tra xem sản phẩm đã tồn tại trong cơ sở dữ liệu chưa
  $sql_check_product = mysqli_query($conn, "SELECT idSP FROM sanpham WHERE TENSP = '$tensp'");
  $num_rows = mysqli_num_rows($sql_check_product);

  if ($num_rows > 0) {  
      //Thông điệp trả về  
      $message = "Sản phửm đã tồn tại rùi kưng";
  } else {
      // Sản phẩm chưa tồn tại, thêm sản phẩm mới
      mysqli_query($conn, "INSERT INTO sanpham
      (idSP, TENSP, HANG, GIANHAP, idDM, IMG, MOTA, GIA) 
      VALUES($num_rows_id, '$tensp', $hang, 0, $danhmuc, '$new_url', '$mota', 0)");

      //Thêm ảnh vào folder images
      move_uploaded_file($img_temp, "$images_dir/$new_url");   
      
      //Thông điệp trả về
      $message = "Đã thêm $tensp vào danh sách dản phửm";
    }
?>