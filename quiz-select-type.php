<?php
  require_once 'element/header.php';
  requireLogin();
  if (isset($_POST['quizSet'])) {
    $setid = intval($_POST['quizSet']);
    $detail_sql = "SELECT * from quiz_set where id={$setid}";
    $detail_res = $mysqli->query($detail_sql);
    $detail = $detail_res->fetch_assoc();
  } else if (isset($_GET['quizSet'])) {
    $setid = intval($_GET['quizSet']);
    $detail_sql = "SELECT * from quiz_set where id={$setid}";
    $detail_res = $mysqli->query($detail_sql);
    $detail = $detail_res->fetch_assoc();
  } else {
    header("Location: quiz.php");
    die();
  }

  // GET EALIER RESULTs
  $r_sql = "SELECT * from quiz_result where _set=$setid and _user={$_SESSION['userid']}";
  $r_res = $mysqli->query($r_sql);
  $v_lock = false; $g_lock = false; $r_lock = false; $l_lock = false;
  $vc_res = intval($mysqli->query("SELECT COUNT(id) as amnt from quiz_question where _set=$setid and type=1")->fetch_assoc()['amnt']);
  $gc_res = intval($mysqli->query("SELECT COUNT(id) as amnt from quiz_question where _set=$setid and type=2")->fetch_assoc()['amnt']);
  $rc_res = intval($mysqli->query("SELECT COUNT(id) as amnt from quiz_question where _set=$setid and type=3")->fetch_assoc()['amnt']);
  $lc_res = intval($mysqli->query("SELECT COUNT(id) as amnt from quiz_question where _set=$setid and type=4")->fetch_assoc()['amnt']);
  if ($r_res->num_rows != 0) {
    $r = $r_res->fetch_assoc();
    $v_lock = $r['v_score'] > -1;
    $g_lock = $r['g_score'] > -1;
    $r_lock = $r['r_score'] > -1;
    $l_lock = $r['l_score'] > -1;
  }
?>
<div class="container" style="text-align: center !important;">
  <form action="quiz-do.php" method="post">
  <input type="hidden" name="setid" value="<?php echo $setid; ?>"/>
  <?php
    echo "<h3>แบบทดสอบ: <b>{$detail['name']}</b></h3>";
    echo "<h5>{$detail['desc']}</h5>";
  ?>
  <div class="row">
    <div class="col s12">
      <?php if ($v_lock && $g_lock && $r_lock && $l_lock) {
        $t_get = $r['v_score'] + $r['g_score'] + $r['r_score'] + $r['l_score'];
        $t_all = $vc_res + $gc_res + $rc_res + $lc_res;
        echo "คุณทำแบบทดสอบนี้แล้ว. ได้ <b>$t_get</b> คะแนน จาก $t_all คะแนน";
      } else {
        echo "เลือกแบบทดสอบ";
      }?>
    </div>
  </div>
  <div class="row">
    <div class="col s12 m8 offset-m2">
      <?php
        if ($v_lock) {
          echo "<div class='q-result red'><h6><i class='fa fa-check-circle-o'></i> Vocaburary Score</h6><span><b>{$r['v_score']}</b> / $vc_res</span></div>";
        } else {
          echo "<button type='submit' class='waves-effect waves-light btn btn-block red' name='quiz-do' value='1'>Vocaburary Quiz</button>";
        }
      ?>
    </div>
  </div>
  <div class="row">
    <div class="col s12 m8 offset-m2">
      <?php
        if ($g_lock) {
          echo "<div class='q-result green'><h6><i class='fa fa-check-circle-o'></i> Grammar Score</h6><span><b>{$r['g_score']}</b> / $gc_res</span></div>";
        } else {
          echo "<button type='submit' class='waves-effect waves-light btn btn-block green' name='quiz-do' value='2'>Grammar Quiz</button>";
        }
      ?>
    </div>
  </div>
  <div class="row">
    <div class="col s12 m8 offset-m2">
      <?php
        if ($r_lock) {
          echo "<div class='q-result teal'><h6><i class='fa fa-check-circle-o'></i> Reading Score</h6><span><b>{$r['r_score']}</b> / $rc_res</span></div>";
        } else {
          echo "<button type='submit' class='waves-effect waves-light btn btn-block teal' name='quiz-do' value='3'>Reading Quiz</button>";
        }
      ?>
    </div>
  </div>
  <div class="row">
    <div class="col s12 m8 offset-m2">
      <?php
        if ($l_lock) {
          echo "<div class='q-result orange'><h6><i class='fa fa-check-circle-o'></i> Listening Score</h6><span><b>{$r['l_score']}</b> / $lc_res</span></div>";
        } else {
          echo "<button type='submit' class='waves-effect waves-light btn btn-block orange' name='quiz-do' value='4'>Listening Quiz</button>";
        }
      ?>
    </div>
  </div>
  <div class="row">
    <div class="col s12 m8 offset-m2">
      <a class="waves-effect waves-light btn btn-block grey" href="quiz.php">ย้อนกลับ</a>
    </div>
  </div>
  </form>
</div>
<?php require_once 'element/footer.php'; ?>
