<?php
  require_once 'setting.php';
  if (isset($_POST)) {
    $currTime = date("Y-m-d H:i:s");
    if(!isset($_POST['existing']))$_POST['existing'] = 0;
    header('Content-type:application/json;charset=utf-8');
    $seatP1 = $_POST['seatP1'];
    $order1 = getOrder($mysqli, $_POST['seatP1']);
    $order1 = $order1 + 1;
    if(isset($_POST['seatP2'])){
      $res_with = $_POST['seatP2'];
    }else{
      $res_with = 0;
    }
    $sql1 = "UPDATE seatOfCourse 
            set     _student={$_SESSION['userid']},
                    existing={$_POST['existing']},
                    res_time='{$currTime}',
                    res_order='{$order1}',
                    res_with={$res_with} 
            where id={$_POST['seatP1']} 
            and _student IS NULL";
    $res1 = $mysqli->query($sql1);
    if(isset($_POST['seatP2'])) {
      $seatP2 = $_POST['seatP2'];
      // $order2 = getOrder($mysqli, $_POST['seatP2']);
      // $order2 = $order2 + 1;
      $sql2 = "UPDATE seatOfCourse set _student={$_SESSION['userid']},existing={$_POST['existing']},res_time='{$currTime}',res_order='{$order1}',res_with={$seatP1} where id={$_POST['seatP2']} and _student IS NULL";
      $res2 = $mysqli->query($sql2);
      if(! $res1 || ! $res2) {
        $result = array('status' => -1, 'desc' => 'error' );
        echo json_encode($result);
      } else {
        $result = array('status' => 0, 'desc' => 'ok' );
        echo json_encode($result);
      }
    } else {
      if(! $res1) {
        $result = array('status' => -1, 'desc' => 'error' );
        echo json_encode($result);
      } else {
        $result = array('status' => 0, 'desc' => 'ok' );
        echo json_encode($result);
      }
    }
  }

  function getOrder($mysqli,$seatId){
    // $sql = "SELECT _course FROM seatOfCourse WHERE id={$seatId}";
    // $res = $mysqli->query($sql);
    // $r = $res->fetch_assoc();
    $sql = "SELECT MAX(res_order) FROM seatOfCourse WHERE _student IS NOT NULL";
    $res = $mysqli->query($sql);
    $r = $res->fetch_assoc();
    return $r['MAX(res_order)'];
  }
?>
