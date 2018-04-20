<?php
  require_once 'setting.php';
  if (isset($_POST['paymentSelect'])){
    header('Content-type:application/json;charset=utf-8');

    // validate can not reserve if already reserve some coruse
    $sql = "SELECT COUNT(seatOfCourse.id) as count
            FROM seatOfCourse
            WHERE seatOfCourse._student='{$_SESSION['userid']}'
            AND _slip IS NOT NULL";
    $res = $mysqli->query($sql);
    $course_reserved = $res->fetch_assoc();
    $sql = "SELECT COUNT(seatOfCourse.id) as count
            FROM seatOfCourse, slip
            WHERE _student='{$_SESSION['userid']}'
            AND seatOfCourse._slip IS NOT NULL
            AND slip.id=seatOfCourse._slip
            AND checked=1";
    $res = $mysqli->query($sql);
    $course_checked = $res->fetch_assoc();
    if ($course_reserved['count'] - $course_checked['count'] > 1){
      header("Location: ../course-normal.php?error=9");
    }

    $course = explode('-', $_POST['helper']);
    $seatid = [];
    $res = $mysqli->query("SELECT id FROM seatOfCourse WHERE _course={$course[0]} AND _student IS NULL AND _seat!=59 LIMIT 1");
    $seatid[] = $res->fetch_assoc()['id'];
    $res = $mysqli->query("SELECT id FROM seatOfCourse WHERE _course={$course[1]} AND _student IS NULL AND _seat!=59 LIMIT 1");
    $seatid[] = $res->fetch_assoc()['id'];
    print_r($seatid);
    if (!isset($seatid[0]) || !isset($seatid[1])){
      header("Location: ../course-normal.php?error=7");
    }

    $target_dir = "../upload/";
    $view_dir = "upload/";
    $filename = date("YmdHisv").'-'.basename($_FILES["slipUpload"]["name"]);
    $target_file = $target_dir . $filename;
    $view_file = $view_dir . $filename;
    $fileType = pathinfo($target_file,PATHINFO_EXTENSION);

    if (move_uploaded_file($_FILES["slipUpload"]["tmp_name"], $target_file)) { // Upload Picture Success
      $mysqli->query("INSERT into slip (file) values ('{$view_file}')");
      $slipId = getSlipId($mysqli, $view_file);

      $seats_empty = true;
      foreach ($seatid as $key => $val) {
        $res = $mysqli->query("SELECT _student FROM seatOfCourse WHERE id={$val}");
        $seats_empty = $seats_empty && ( $res->fetch_assoc()['_student'] == NULL );
      }

      if($seats_empty){
        $currTime = date("Y-m-d H:i:s");
        $order = getLastOrder($mysqli) + 1;
        $res = [];
        foreach ($seatid as $key => $val) {
          $res_with = $seatid[($key+1)%2];
          $sql = "	UPDATE 	seatOfCourse
                    SET 		_student={$_SESSION['userid']}, existing=0,
                            res_time='{$currTime}',
                            res_order={$order},
                            res_with={$res_with},
                            slipUploaded=1,
                            _slip={$slipId}
                    WHERE		id={$val}";
          $res[] = $mysqli->query($sql);
        }

        if($res[0] && $res[1]){ // Success
          header("Location: ../?success=4");
        }else{ // Reserved error
          header("Location: ../course-normal.php?error=8");
        }

      }else{ // Seat not empty
        header("Location: ../course-normal.php?error=7");
      }

    }else{ // Upload error
      header("Location: ../course-normal.php?error=2");
    }
  }

  function getSlipId($mysqli, $view_file){
    $res = $mysqli->query("SELECT id FROM slip WHERE file='{$view_file}'");
    $r = $res->fetch_assoc();
    return $r['id'];
  }

	function getLastOrder($mysqli){
    $sql = "SELECT MAX(res_order) FROM seatOfCourse WHERE _student IS NOT NULL";
    $res = $mysqli->query($sql);
    $r = $res->fetch_assoc();
    return $r['MAX(res_order)'];
  }
?>
