<?php
  require_once 'element/header.php';
  requireLogin();
  if (isset($_POST['quiz-do'])) {
    $setid = intval($_POST['setid']);
    $quiztype = intval($_POST['quiz-do']);
    $q_sql = "SELECT * from quiz_question where _set={$setid} AND type={$quiztype}";
    $q_res = $mysqli->query($q_sql);
  } else if (isset($_POST['sendQuiz'])) { // quiz submission
    $s_q_type = intval($_POST['quiztype']);
    $quiztype = intval($_POST['quiztype']);
    $s_q_setid = intval($_POST['setid']);
    $setid = intval($_POST['setid']);
    $s_sql = "SELECT id, correctchoice from quiz_question where _set=$s_q_setid AND type=$s_q_type";
    $s_res = $mysqli->query($s_sql);
    $score = 0;
    while ($s_eval = $s_res->fetch_assoc()) {
      if ($s_eval['correctchoice'] == $_POST[$s_eval['id']]) {
        $score++;
      }
    }
    $r_c_sql = "SELECT * from quiz_result where _set=$s_q_setid AND _user={$_SESSION['userid']}";
    $r_c_res = $mysqli->query($r_c_sql);
    if ($r_c_res->num_rows == 0) {
      $v_score = -1; $g_score = -1; $r_score = -1; $l_score = -1;
      switch ($s_q_type) {
        case 1: $v_score = $score; break;
        case 2: $g_score = $score; break;
        case 3: $r_score = $score; break;
        case 4: $l_score = $score; break;
      }
      $r_sql = "INSERT into quiz_result (_set, _user, v_score, g_score, r_score, l_score) values ($s_q_setid, {$_SESSION['userid']}, $v_score, $g_score, $r_score, $l_score)";
    } else { // there is record so, update it
      $rec = $r_c_res->fetch_assoc();
      $upd = '';
      switch ($s_q_type) {
        case 1: $upd = "v_score"; break;
        case 2: $upd = "g_score"; break;
        case 3: $upd = "r_score"; break;
        case 4: $upd = "l_score"; break;
      }
      $r_sql = "UPDATE quiz_result set $upd=$score where id={$rec['id']}";
    }
    $r_res = $mysqli->query($r_sql);
    header("Location: quiz-select-type.php?quizSet=$s_q_setid");
    die();
  } else {
    header("Location: quiz.php");
    die();
  }
?>
<div class="container">
  <h3>แบบทดสอบ <b><?php
  switch ($quiztype) {
    case 1: echo "Vocaburary"; break;
    case 2: echo "Grammar"; break;
    case 3: echo "Reading"; break;
    case 4: echo "Listening"; break;

    default: die(); break;
  }
  ?></b></h3>
  <?php
    if ($quiztype == 4) {
      $sound_sql = "SELECT file from quiz_question where _set=$setid AND type=4";
      $sound_res = $mysqli->query($sound_sql);
      $sound = $sound_res->fetch_assoc();
      echo "<p>กรุณาฟังและตอบคำถาม</p>";
      echo "<div class='row'><audio controls><source src='{$sound['file']}'>Your browser does not support the audio element.</audio></div>";
    }
  ?>
  <form action="quiz-do.php" method="post">
    <?php
    echo "<input type='hidden' name='setid' value='{$setid}'/>";
    echo "<input type='hidden' name='quiztype' value='{$quiztype}'/>";
    $qi = 1;
    while ($q = $q_res->fetch_assoc()) {
      echo "<div class='row'>";
      echo "<div class='col s12'><h5>{$qi}. <b>{$q['question']}</b></h5></div>";
      for ($i=1; $i <= 4; $i++) {
        echo "<div class='col s12 m6 choice'><input name='{$q['id']}' type='radio' id='{$q['id']}-$i' value='$i' required/><label for='{$q['id']}-$i'>{$q['choice'.$i]}</label></div>";
      }
      echo "</div>";
      $qi++;
    }
    ?>
  <div class="row">
    <div class="col s12">
      <div style="text-align: center">กรุณาตรวจสอบคำตอบให้เรียบร้อย เมื่อส่งคำตอบแล้วระบบจะบันทึกไว้โดยถาวร</div>
      <button type="submit" class="waves-effect waves-light btn btn-block black" name="sendQuiz">ยืนยัน</button>
    </div>
  </div>
  </form>
  <div class="row">
    <div class="col s12">
      <form action="quiz-select-type.php" method="post">
        <input type="hidden" name="quizSet" value="<?php echo $setid; ?>"/>
        <button type="submit" name="selectQuizSet" class="waves-effect waves-light btn btn-block grey">ยกเลิก</button>
      </form>
    </div>
  </div>
</div>
<?php require_once 'element/footer.php'; ?>
