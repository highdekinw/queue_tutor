<?php
  require_once 'setting.php';

  function noPostData() {
    header("Location: ../");
    session_destroy();
    die();
  }

  if (isset($_POST) && isset($_POST['id']) && isset($_POST['password'])) {
    $user = mysqli_real_escape_string($mysqli, $_POST['id']);
    $pass = mysqli_real_escape_string($mysqli, $_POST['password']);

    print_r($_POST);
    $sql = "SELECT * FROM user WHERE username='{$user}' AND password='{$pass}' AND inuse=1";
    $res = $mysqli->query($sql);
    $u = $res->fetch_assoc();
    if (isset($u)) {
      $_SESSION['username'] = $u['username'];
      $_SESSION['userid'] = $u['id'];
      $_SESSION['usertype'] = $u['status'];
      $_SESSION['firstname'] = $u['firstname'];
      $_SESSION['lastname'] = $u['lastname'];
      header("Location: ../");
    } else {
      header("Location: ../?error=1");
    }
  } else {
    noPostData();
  }
?>
