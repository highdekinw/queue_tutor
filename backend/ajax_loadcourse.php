<?php
  require_once 'setting.php';

  $res = $mysqli->query("SELECT * from course,term WHERE course._year=term.year AND course._term=term.term");
  $courses = array();
  while ($course = $res->fetch_assoc()) {
    array_push($courses, $course);
  }
  header('Content-type:application/json;charset=utf-8');
  echo json_encode($courses);
?>
