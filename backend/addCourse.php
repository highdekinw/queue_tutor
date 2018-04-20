<?php
  require_once 'setting.php';
  if (isset($_GET['delete'])) {
    $sql = "delete from course where id={$_GET['delete']}";
    $mysqli->query($sql);
    header('Location: ../manage-course.php?success=6');
  } else if (isset($_POST['addcourse'])){
    $name = $_POST['courseName'];
    $type = $_POST['courseType'];
    $price = $_POST['coursePrice'];
    $time_start = $_POST['startTime'];
    $time_end = $_POST['endTime'];
    $date_start = $_POST['startDate'];
    $date_end = $_POST['endDate'];
    $period = $_POST['coursePeriod'];
    $_room = $_POST['room'];
    $day = array('sun' => 0, 'mon' => 0, 'tue' => 0, 'wed' => 0, 'thu' => 0, 'fri' => 0, 'sat' => 0);
    $nDay = count($_POST['day']);
    $expl = explode("-", $_POST['term']);
    $term = $expl[0];
    $year = $expl[1];
    $subject = $_POST['course_select'];
    $description = $_POST['course_desc'];
    $section = $_POST['course_section'];
    if ($description == '') $description = '-';
    foreach( $day as $key => $val){
        if($_POST[$key] == 'true')$day[$key] = 1;
    }
    $sql = "insert into course (name, type, period, price, time_start, time_end, date_start, date_end, sun, mon, tue, wed, thu, fri, sat, _room, _year, _term, subject, description, section)
    values ('$name', $type, $period, $price, '$time_start', '$time_end', '$date_start', '$date_end',
            {$day['sun']}, {$day['mon']}, {$day['tue']}, {$day['wed']}, {$day['thu']}, {$day['fri']}, {$day['sat']},
            $_room, $year, $term, $subject, '{$description}', $section)";
    $mysqli->query($sql);
    $courseid = $mysqli->insert_id;

    $sqlroom = "select * from seat where _room={$_room}";
    $resroom = $mysqli->query($sqlroom);
    while ($roomLink = $resroom->fetch_assoc()) {
      $sqlseatinsert = "INSERT INTO seatOfCourse (_seat, _course) VALUES ({$roomLink['id']}, {$courseid})";
      $mysqli->query($sqlseatinsert);
    }

    header('Location: ../manage-course.php?success=5');
  } else if (isset($_POST['editcourse'])) {
    $id = $_POST['editid'];
    $name = $_POST['courseName'];
    $type = $_POST['courseType'];
    $price = $_POST['coursePrice'];
    $time_start = $_POST['startTime'];
    $time_end = $_POST['endTime'];
    $date_start = $_POST['startDate'];
    $date_end = $_POST['endDate'];
    $period = $_POST['coursePeriod'];
    $_room = $_POST['room'];
    $day = array('sun' => 0, 'mon' => 0, 'tue' => 0, 'wed' => 0, 'thu' => 0, 'fri' => 0, 'sat' => 0);
    $nDay = count($_POST['day']);
    $expl = explode("-", $_POST['term']);
    $term = $expl[0];
    $year = $expl[1];
    $description = $_POST['course_desc'];
    $section = $_POST['course_section'];
    if ($description == '') $description = '-';
    echo $description;
    // for ($i=0; $i < $nDay; $i++) {
    //   $day[$_POST['day'][$i]] = 1;
    // }
    foreach( $day as $key => $val){
        if($_POST[$key] == 'true')$day[$key] = 1;
    }
    $sql = "UPDATE course set period={$period}, price={$price},
    time_start='{$time_start}', time_end='{$time_end}', date_start='{$date_start}', date_end='{$date_end}',
    sun={$day['sun']}, mon={$day['mon']}, tue={$day['tue']}, wed={$day['wed']}, thu={$day['thu']}, fri={$day['fri']}, sat={$day['sat']}, _room=$_room, _year='{$year}', _term='{$term}', description='{$description}', section=$section
    WHERE id={$id}";
    $mysqli->query($sql);
    header('Location: ../manage-course.php?success=4');
  } else {
    header('Location: ../manage-course.php');
  }
?>
