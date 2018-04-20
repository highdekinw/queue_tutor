<?php
require_once 'setting.php';
$seatid = explode('-', $_POST['helper']);
if (isset($_POST['paymentSelect'])) {
    $target_dir = "../upload/";
    $view_dir = "upload/";
    $filename = date("YmdHisv").'-'.basename($_FILES["slipUpload"]["name"]);
    $target_file = $target_dir . $filename;
    $view_file = $view_dir . $filename;
    $fileType = pathinfo($target_file,PATHINFO_EXTENSION);
    if (move_uploaded_file($_FILES["slipUpload"]["tmp_name"], $target_file)) {
        $mysqli->query("INSERT into slip (file) values ('{$view_file}')");
        $slipId = getSlipId($mysqli, $view_file);
        foreach ($seatid as $key => $val) {
            $mysqli->query("UPDATE seatOfCourse set slipUploaded=1,_slip={$slipId} where id={$val}");
        }
        header("Location: ../?success=2");
      } else {
        $delsql = "UPDATE seatOfCourse set _student=NULL, res_order=0, res_with=0, _slip=NULL where _student={$_SESSION['userid']}";
        $mysqli->query($delsql);
        header("Location: ../?error=2");
      }
  }

  function getSlipId($mysqli, $view_file){
    $res = $mysqli->query("SELECT id FROM slip WHERE file='{$view_file}'");
    $r = $res->fetch_assoc();
    return $r['id'];
  }

?>