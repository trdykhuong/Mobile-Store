<?php 
$conn=mysqli_connect("localhost:3306", "root", "", "chdidong");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $json = file_get_contents('php://input');
    $data = json_decode($json, true);

    $id = isset($data['id']) ? $data['id'] : null;

    $sql = 'SELECT tk.idTK, tk.HOTEN, tk.SDT, tk.EMAIL, nv.GIOITINH, nv.NGAYSINH, nv.NGAYSINH, nv.DIACHI, nv.IMG, nv.NGAYVAOLAM, nv.TINHTRANG, q.TENQUYEN, q.LUONGCB from taikhoan tk 
            JOIN quyen q ON tk.idQUYEN=q.idQUYEN JOIN nhanvien nv 
            ON tk.idTK=nv.idTK WHERE tk.idTK=' . intval($id);

    $employee = mysqli_query($conn, $sql);

    if(mysqli_num_rows($employee) > 0){

        $info = array();
        while($employee_rows = mysqli_fetch_array($employee)){
            $idnv = $employee_rows['idTK'];
            $hoten = $employee_rows['HOTEN'];
            $email = $employee_rows['EMAIL'];
            $sdt = $employee_rows['SDT'];

            $gioitinh = $employee_rows['GIOITINH'];
            $ngaysinh = $employee_rows['NGAYSINH'];
            $diachi = $employee_rows['DIACHI'];
            $img = $employee_rows['IMG'];
            $ngayvaolam = $employee_rows['NGAYVAOLAM'];
            $tinhtrang = $employee_rows['TINHTRANG'];

            $quyen = $employee_rows['TENQUYEN'];
            $luong = $employee_rows['LUONGCB'];

            //Push vào array
            $info = array(
                'id' => $idnv,
                'hoten' => $hoten,
                'email' => $email,  
                'sdt' => $sdt,  
                'gioitinh' => $gioitinh, 
                'ngaysinh' => $ngaysinh,  
                'diachi' => $diachi,
                'img' => $img,
                'ngayvaolam' => $ngayvaolam,
                'tinhtrang' => $tinhtrang,  
                'quyen' => $quyen,  
                'luong' => $luong,  
            ); 
        }

        $json = json_encode($info);
        echo $json;
    }

}
?>