<?php

class userAuth
{
  public $view_permission_list = [];
  public $add_permission_list = [];
  public $update_permission_list = [];
  public $delete_permission_list = [];
  public $conn;

  function __construct($conn)
  {
    $this->conn = $conn;
    $this->getPermisstionList();

  }

  function isAdmin() {
    if (count($this->view_permission_list) == 0) {
      return false;
    } else return true;
  }

  function getPermisstionList()
  {
    $role_id = null;
    if (session_status() == PHP_SESSION_NONE) {
      session_start();
    }
    if (isset($_SESSION['userdata'])) {
      $role_id = $_SESSION['userdata']['maquyen'];
      $sql = "SELECT Machucnang,Thaotac FROM chitietquyen WHERE Maquyen ='$role_id'";
      $role_features_actions = $this->conn->query($sql);
      while ($row = $role_features_actions->fetch_assoc()) {
        if ($row['Thaotac'] == 'view') {
          $this->view_permission_list[] = $row['Machucnang'];
        }
        if ($row['Thaotac'] == 'add') {
          $this->add_permission_list[] = $row['Machucnang'];
        }
        if ($row['Thaotac'] == 'update') {
          $this->update_permission_list[] = $row['Machucnang'];
        }
        if ($row['Thaotac'] == 'delete') {
          $this->delete_permission_list[] = $row['Machucnang'];
        }
      }
    } else {
      header("Location:../user/php/dangnhap.php");
      exit();
    }
  }

  function checkReadPermission($feature_id)
  {
    if (in_array($feature_id, $this->view_permission_list)) {
      return;
    } else {
      header("Location:../index.php");
      exit();
    }
  }

  function checkCreatePermission($feature_id)
  {
    if (in_array($feature_id, $this->add_permission_list)) {
      return true;
    } else {
      return false;
    }
  }

  function checkUpdatePermission($feature_id)
  {
    if (in_array($feature_id, $this->update_permission_list)) {
      return true;
    } else {
      return false;
    }
  }

  function checkDeletePermission($feature_id)
  {
    if (in_array($feature_id, $this->delete_permission_list)) {
      return true;
    } else {
      return false;
    }
  }
}
