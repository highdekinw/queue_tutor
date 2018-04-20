<?php
  require_once 'setting.php';
  $sql = "SELECT * from user where inuse=1 and status<>1 ORDER BY username ASC";
  $res = $mysqli->query($sql);
  $users = array();
  while ($user = $res->fetch_assoc()) {
    array_push($users, $user);
  }
  header('Content-type:application/json;charset=utf-8');
  echo json_encode($users);
?>
