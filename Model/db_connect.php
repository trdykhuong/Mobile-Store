<?php
class Database
{
  private $host;
  private $username;
  private $password;
  private $dbname;
  private $conn = NULL;

  private $result = NULL;

  public function connect()
  {
    $this->host = "localhost";
    $this->username = "root";
    $this->password = "";
    $this->dbname = "chdidong";
    $this->conn = new mysqli($this->host, $this->username, $this->password, $this->dbname);

    if ($this->conn->connect_error) {
      die("Kết nối không thành công: " . $this->conn->connect_error);
    }

    return $this->conn; // Trả về kết nối
  }

 


  public function getError()
{
    return $this->conn->error;
}

public function prepare($sql)
{
    if (!$this->conn) {
        die("Lỗi: Chưa kết nối đến cơ sở dữ liệu.");
    }

    $stmt = $this->conn->prepare($sql);

    if (!$stmt) {
        die("Lỗi chuẩn bị truy vấn: " . $this->getError());
    }

    return $stmt;
}
  public function query($sql)
  {
    $result = $this->conn->query($sql);

    // Kiểm tra và xử lý lỗi nếu có
    if (!$result) {
      die("Lỗi truy vấn: " . $this->conn->error);
    }

    return $result;
  }

  public function insert($sql)
  {
    if ($this->conn->query($sql) === TRUE) {
      return $this->conn->insert_id;
    } else {
      return false;
    }
  }

  // Phương thức ngắt kết nối
  public function close()
  {
    $this->conn->close();
  }


   //lấy dữ liệu
   public function getData()
   {
     if ($this->result) {
       $data = mysqli_fetch_array($this->result);
     } else {
       $data = 0;
     }
     return $data;
   }

  public function num_rows()
  {
    if ($this->result) {
      $num = mysqli_num_rows($this->result);
    } else {
      $num = 0;
    }
    return $num;
  }


  //lấy toàn bộ dữ liệu
  public function getAllData($table)
  {
    $sql = "SELECT * FROM $table";
    $this->execute($sql);
    if ($this->num_rows() == 0) {
      $data = 0;
    } else {
      while ($datas = $this->getData()) {
        $data[] = $datas;
      }
    }
    return $data;
  }



   //thực thi câu truy vấn
   public function execute($sql)
   {
     $this->result = $this->conn->query($sql);
     return $this->result;
   }

  public function num_row()
  { // co them s-> thieus 
    if ($this->result) {

      $num = mysqli_num_rows($this->result);
    } else {
      $num = 0;
    }
    return $num;
  }

  public function getAllDataBySql($sql)
  {
      $result = $this->query($sql); // Gọi query() để xử lý lỗi
      $data = [];
  
      while ($row = $result->fetch_assoc()) {
          $data[] = $row;
      }
  
      return $data;
  }














// adminnnnnnnnnnnnnnnnnn

// thêm chức năng
public function InsertChucNang($name)
{
  $sql = "INSERT INTO chucnang (decription) VALUES ('$name')";
  return $this->execute($sql);
}
// sửa chức năng
public function UpdateNameChucNang($id, $name)
{
  $sql = "UPDATE chucnang SET decription = '$name' WHERE id = $id";
  return $this->execute($sql);
}
//xóa chức năng
public function DeleteChucNang($id)
{
  $sql = "DELETE FROM chucnang WHERE id = $id";
  return $this->execute($sql);
}

// thêm quyền
public function InsertRole($name)
{
  $sql = "INSERT INTO role (decription) VALUES ('$name')";
  $rs = $this->execute($sql);
  if ($rs) {
    return true;
  } else
    return false;
}
// update role
public function UpdateNameRole($id, $name)
{
  $sql = "UPDATE role SET decription = '$name' WHERE id = $id";
  // $rs = $this->execute($sql);
  // $affectedRows = mysqli_affected_rows($this->conn);
  $stmt = $this->conn->prepare($sql);
  $stmt->execute();
  $affectedRows = $stmt->affected_rows;
  // Nếu có ít nhất một dòng được cập nhật, trả về true
  if ($affectedRows > 0) {
    return true;
  } else {
    return false;
  }
}
// tìm role theo id
public function FindRole($idRole)
{
  $sql = "select * from phanquyenlinhdong where id_role = $idRole";
  return $this->execute($sql);
}
//xóa role
public function DeleteRole($id)
{
  $sql1 = "DELETE FROM phanquyenlinhdong WHERE id_role = $id";
  $this->execute($sql1);
  $sql = "DELETE FROM role WHERE id = $id";
  return $this->execute($sql);
}
public function DeleteRoleLinhDong($id)
{
  $sql1 = "DELETE FROM phanquyenlinhdong WHERE id_role = $id";
  return $this->execute($sql1);
}
public function UpdateRoleLinhDong($idRole, $id_CN, $HD)
{
  $sql = "INSERT INTO phanquyenlinhdong (id_role, id_chucNang, HD) values ('$idRole', '$id_CN', '$HD')";

  // $rs = $this->execute($sql);
  // $affectedRows = mysqli_affected_rows($this->conn);
  $stmt = $this->conn->prepare($sql);
  $stmt->execute();
  $affectedRows = $stmt->affected_rows;
  // Nếu có ít nhất một dòng được cập nhật, trả về true
  if ($affectedRows > 0) {
    return true;
  } else {
    return false;
  }
}
public function InsertRoleLinhDong($id_CN, $HD)
{
  $selectId = "SELECT id FROM role ORDER BY id DESC LIMIT 1";
  $result = $this->execute($selectId);
  $row = $result->fetch_assoc();
  $last_role_id = $row["id"];
  $sql = "INSERT INTO phanquyenlinhdong (id_role, id_chucNang, HD) values ('$last_role_id', '$id_CN', '$HD')";
  return $this->execute($sql);
}
// check quyền mới cho nhấn tabAdmin
public function checkRoleAdmin($idAccount)
{
  $sql = "SELECT * 
FROM acount
JOIN phanquyenlinhdong ON acount.id_role = phanquyenlinhdong.id_role 
WHERE acount.id = $idAccount;";
  $result = $this->execute($sql);
  $roles = array(); // Mảng chứa các role

  while ($row = mysqli_fetch_array($result)) {
    $roles[] = $row; // Thêm hàng dữ liệu vào mảng roles
  }

  return $roles;
}

  
}




