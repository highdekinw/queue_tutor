<?php
  require_once 'setting.php';
  if(isset($_POST)){
      $sql = "SELECT  course.name as courseName,
                  course.price,
                  course.type,
                  course.time_start,
                  course.time_end,
                  course.period,
                  course._year as _year,
                  course._term as _term,
                  seat.name as seatName,
                  seatOfCourse.id,
                  seatOfCourse.existing,
                  room.name as roomName
          from    course, seatOfCourse, seat, room
          where   seatOfCourse._student = {$_SESSION['userid']}
          AND     seatOfCourse._course = course.id
          AND     seatOfCourse._seat = seat.id
          AND     seatOfCourse.slipUploaded = 0
          AND     room.id = course._room";
    $res = $mysqli->query($sql);
    $json = [];
    while($r = $res->fetch_assoc()){
        $json[] = $r;
    }
    echo json_encode($json);
  }
?>