<?php
  require_once 'setting.php';
  if (isset($_POST)) {
    $courseid = $_POST['courseid'];
    $sql = "SELECT  seatOfCourse.id, 
                    seat.name, 
                    seatOfCourse._student, 
                    seat._room 
            from seat,seatOfCourse 
            where seatOfCourse._seat = seat.id 
            AND seatOfCourse._course={$courseid}
            order by seatOfCourse.id ASC";
      //AND seatOfCourse._student IS NULL";
    $res = $mysqli->query($sql);
    $seats = [];
    while ($seat = $res->fetch_assoc()) {
      if($seat['_student'] != NULL){
        $r = $mysqli->query("SELECT username FROM user WHERE id={$seat['_student']}")->fetch_assoc()['username'];
          $seat['username'] = $r;
      }
      $seats[] = $seat;
    }
    header('Content-type:application/json;charset=utf-8');
    echo json_encode($seats);
  }
?>
