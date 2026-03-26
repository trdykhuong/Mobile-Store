<?php
$conn = mysqli_connect("localhost:3306", "root", "", "chdidong");
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $json = file_get_contents('php://input');
    $data = json_decode($json, true);

    $text = isset($data['text'])? $data['text'] : '';
    $hang = isset($data['hang'])? intval($data['hang']) : 0;
    $danhmuc = isset($data['danhmuc'])? intval($data['danhmuc']) : 0;
    $giatu = isset($data['giatu'])? intval($data['giatu']) : 0;
    $giaden = isset($data['giaden'])? intval($data['giaden']) : 0;
    $order = isset($data['order'])? intval($data['order']) : 1;

    //Móc ra sản phẩm
    $sql = 'SELECT s.idSP, s.TENSP, s.IMG, s.GIANHAP, s.GIA, s.DISCOUNT,s.TRANGTHAI, h.TENHANG, d.LOAISP from sanpham s JOIN hang h ON s.HANG = h.idHANG
    JOIN danhmuc d ON s.idDm = d.idDM ';
    //Khởi tạo mấy cái sql
    $sql_queue = "";
    
    if (($hang + $danhmuc) > 0) { // Có chọn hãng hoặc danh mục hoặc cả hai
        $sql_queue = " WHERE";

        if (($hang > 0) && ($danhmuc > 0)) { // Chọn cả hãng và danh mục
            $sql_queue .= (" s.HANG=" . intval($hang) . " AND s.idDM=" . intval($danhmuc));
        } else {
            // Chỉ chọn một trong hai
            $sql_queue .= ($hang == 0 ? (" s.idDM=" . intval($danhmuc)) : (" s.HANG=" . intval($hang)));
        }

        // Có nhập giá nhập
        $sql_queue .= ($giatu > 0 ? (" AND s.GIANHAP BETWEEN " . intval($giatu) . " AND " . intval($giaden)) : "");
    } else {
        // Không chọn hãng và danh mục
        $sql_queue .= $giatu > 0 ? (" WHERE s.GIANHAP BETWEEN " . intval($giatu) . " AND " . intval($giaden)) : "";
    }

    $sql_search = ($text != "" ? (" AND s.TENSP LIKE '%$text%'") : "");

    // Kiểm tra input tìm kiếm
    if (intval($hang) + intval($danhmuc) + intval($giaden) + intval($giatu) == 0 && $text != "") {
        $sql_search = " WHERE s.TENSP LIKE '%$text%'";
    }

    $sql_order = (" AND s.TRANGTHAI = $order ");

    if (intval($hang) + intval($danhmuc) + intval($giaden) + intval($giatu) == 0 && $text == "") {
        $sql_order = " WHERE s.TRANGTHAI = $order ";
    }



    //Nối mấy sql lại
    $sql .= ($sql_queue . $sql_search . $sql_order . " ORDER BY s.idSP ASC");

    ///Chạy sql
    $result = mysqli_query($conn, $sql);
    $productlist = array();
    //Chạy truy vấn lưu từng sp vào mảng
    while($sp = mysqli_fetch_array($result)){
        $idsp = $sp['idSP'];
        $tensp = $sp['TENSP'];
        $img = $sp['IMG'];
        $gianhap = $sp['GIANHAP'];
        $giaban = $sp['GIA'];
        $discount = $sp['DISCOUNT'];
        $hang = $sp['TENHANG'];
        $dm = $sp['LOAISP'];
        $trangthai = $sp['TRANGTHAI'];

        $arr = ['id' => $idsp,
            'name' => $tensp,
            'img' => $img,
            'gianhap' => $gianhap,
            'giaban' => $giaban,
            'discount' => $discount,
            'hang' => $hang,
            'danhmuc' => $dm,
            'trangthai' => $trangthai
        ];

        array_push($productlist, $arr);
    }
     
    // //Trả về json 
    // $response = array(
    //     'status' => 'success',
    //     'message' => $sql 
    // );
    // $json = json_encode($response);
    $json = json_encode($productlist);
    echo $json;
}
?>