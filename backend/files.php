<?php
require_once 'setting.php';

if(isset($_POST["upload"])) {
  $target_dir = "../upload/";
  $target_file = $target_dir .date("YmdHisv").'-'. basename($_FILES["fileUpload"]["name"]);
  $filename = basename($_FILES["fileUpload"]["name"]);
  $fileType = pathinfo($target_file,PATHINFO_EXTENSION);
  if (file_exists($target_file)) {
    echo "Sorry, file already exists.";
    $uploadOk = 0;
  }
  if (move_uploaded_file($_FILES["fileUpload"]["tmp_name"], $target_file)) {
      $mysqli->query("insert into files (filename, path) values ('{$filename}','{$target_file}')");
      echo "The file ". basename( $_FILES["fileUpload"]["name"]). " has been uploaded.";
      header("Location: ../manage-download.php");
    } else {
        echo "Sorry, there was an error uploading your file. ".$target_file;
        header("Location: ../manage-download.php?error=2");
    }
}

if (isset($_GET['delete'])) {
  $sql = "select * from files where id={$_GET['delete']}";
  $res = $mysqli->query($sql);
  $file = $res->fetch_assoc();
  unlink($file['path']);
  $sql2 = "delete from files where id={$_GET['delete']}";
  $mysqli->query($sql2);
  header("Location: ../manage-download.php");
}
?>
