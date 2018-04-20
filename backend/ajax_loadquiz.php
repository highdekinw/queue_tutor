<?php
  require_once 'setting.php';
  header('Content-type:application/json;charset=utf-8');
  if (isset($_POST['setid'])) {
    $res = $mysqli->query("SELECT * from quiz_question where _set={$_POST['setid']}");
    $questions = array();
    while ($question = $res->fetch_assoc()) {
      array_push($questions, $question);
    }
    echo json_encode($questions);
  } else {
    echo "{}";
  }
?>
