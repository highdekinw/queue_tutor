<?php
  require_once 'element/header.php';
  requireUserType(1);

  if (isset($_GET['setid'])) {
    $check_sql = "SELECT * from quiz_set where id={$_GET['setid']}";
    $check_res = $mysqli->query($check_sql);
    if ($check_res->num_rows != 1) { // set id is invalid !
      header("Location: manage-quiz.php");
    }
  } else if (isset($_POST['q_saveAll'])) { // TODO: Check if there is question / if is, delete then insert new one instead
    $setid = intval($_POST['qid']);
    $maxV = intval($_POST['maxV']);
    $maxG = intval($_POST['maxG']);
    $maxR = intval($_POST['maxR']);
    $maxL = intval($_POST['maxL']);

    $types = array('v', 'g', 'r', 'l');
    $maxes = array($maxV, $maxG, $maxR, $maxL);
    $target_file = "";

    if (isset($_POST['editid'])) {
      $old = $mysqli->query("SELECT file from quiz_question where _set={$setid} AND type=4");
      $oldfile = $old->fetch_assoc();
      $target_file = $oldfile['file'];
    }
    $mysqli->query("DELETE from quiz_question where _set={$setid}");

    if ($_FILES['listeningFile']['error'] == 0) { // if there is sound file
      if (isset($_POST['editid']) && $target_file != "") { // if old file exist, them remove
        unlink($target_file);
      }
      $target_dir = "upload/";
      $filename = date("YmdHisv").'-'.basename($_FILES["listeningFile"]["name"]);
      $target_file = $target_dir . $filename;
      $fileType = pathinfo($target_file,PATHINFO_EXTENSION);
      if (!move_uploaded_file($_FILES["listeningFile"]["tmp_name"], $target_file)) {
        die("File upload error! ".$_FILES["listeningFile"]["error"]);
      }
    }

    for($j=0; $j < 4; $j++) {
      for ($i=0; $i < $maxes[$j]; $i++) {
        if (isset($_POST[$types[$j].'-q-'.$i])) {
          $question = filter_var($_POST[$types[$j].'-q-'.$i], FILTER_SANITIZE_STRING);
          $ans1 = filter_var($_POST[$types[$j].'-ans-'.$i.'-1'], FILTER_SANITIZE_STRING);
          $ans2 = filter_var($_POST[$types[$j].'-ans-'.$i.'-2'], FILTER_SANITIZE_STRING);
          $ans3 = filter_var($_POST[$types[$j].'-ans-'.$i.'-3'], FILTER_SANITIZE_STRING);
          $ans4 = filter_var($_POST[$types[$j].'-ans-'.$i.'-4'], FILTER_SANITIZE_STRING);
          $correct = intval($_POST[$types[$j].'-c-'.$i]);
          $t = $j + 1;
          if ($types[$j] == 'l' && $_FILES['listeningFile']['error'] == 0) { // if there is sound file
            $sql = "INSERT into quiz_question (_set, type, question, choice1, choice2, choice3, choice4, correctchoice, file) values ($setid, $t, '{$question}', '$ans1', '$ans2', '$ans3', '$ans4', $correct, '$target_file')";
          } else {
            $sql = "INSERT into quiz_question (_set, type, question, choice1, choice2, choice3, choice4, correctchoice) values ($setid, $t, '{$question}', '$ans1', '$ans2', '$ans3', '$ans4', $correct)";
          }
          $mysqli->query($sql);
        }
      }
    }
    header("Location: manage-quiz.php?success=4");
    die();
  } else if (isset($_GET['editSet'])) {
    $_GET['setid'] = $_GET['editSet'];
    $check_sql = "SELECT * from quiz_set where id={$_GET['editSet']}";
    $check_res = $mysqli->query($check_sql);
    if ($check_res->num_rows != 1) { // set id is invalid !
      header("Location: manage-quiz.php");
      die();
    }
  } else {
    header("Location: manage-quiz.php");
    die();
  }
?>
<div class="container">
  <h2>โจทย์สำหรับแบบทดสอบ</h2>
  <form class="col s12" action="manage-quiz-edit.php" id="quiz_set_question" method="post" enctype="multipart/form-data">
  <?php
    echo "<input type='hidden' value='{$_GET['setid']}' name='qid'/>";
    if (isset($_GET['editSet'])) {
      echo "<input type='hidden' value='{$_GET['setid']}' name='editid' id='editidhidden'/>";
    }
  ?>
  <input type='hidden' value='0' name='maxV' id='maxV'/>
  <input type='hidden' value='0' name='maxG' id='maxG'/>
  <input type='hidden' value='0' name='maxR' id='maxR'/>
  <input type='hidden' value='0' name='maxL' id='maxL'/>
  <div class="row z-depth-2" id="questionsContainer">
    <div class="col s12">
      <ul class="tabs">
        <li class="tab col s3"><a class="active" href="#vocab_tab">Vocab</a></li>
        <li class="tab col s3"><a href="#grammar_tab">Grammar</a></li>
        <li class="tab col s3"><a href="#reading_tab">Reading</a></li>
        <li class="tab col s3"><a href="#listening_tab">Listening</a></li>
      </ul>
    </div>
    <div class="col s12" id="vocab_tab">
      <h5 class="modalTitleCenter">Vocaburary</h5>
      <div id="vocab_questions">

      </div>
      <div class="addQuesBtnWrapper">
        <a class="btn-floating btn-large waves-effect waves-light red addQuestionFloatBtn" data-addtype="v"><i class="material-icons">add</i></a>
      </div>
    </div>
    <div class="col s12" id="grammar_tab">
      <h5 class="modalTitleCenter">Grammar</h5>
      <div id="grammar_questions">

      </div>
      <div class="addQuesBtnWrapper">
        <a class="btn-floating btn-large waves-effect waves-light green addQuestionFloatBtn" data-addtype="g"><i class="material-icons">add</i></a>
      </div>
    </div>
    <div class="col s12" id="reading_tab">
      <h5 class="modalTitleCenter">Reading</h5>
      <div id="reading_questions">

      </div>
      <div class="addQuesBtnWrapper">
        <a class="btn-floating btn-large waves-effect waves-light teal addQuestionFloatBtn" data-addtype="r"><i class="material-icons">add</i></a>
      </div>
    </div>
    <div class="col s12" id="listening_tab">
      <h5 class="modalTitleCenter">Listening</h5>
      <div id="oldSound">

      </div>
      <div class="file-field input-field">
        <div class="btn">
          <span>เลือกไฟล์เสียง</span>
          <input type="file" name="listeningFile" accept="audio/*">
        </div>
        <div class="file-path-wrapper">
          <input class="file-path validate" type="text">
        </div>
      </div>
      <div id="listening_questions">

      </div>
      <div class="addQuesBtnWrapper">
        <a class="btn-floating btn-large waves-effect waves-light orange addQuestionFloatBtn" data-addtype="l"><i class="material-icons">add</i></a>
      </div>
    </div>
  </div>
  <div class="row">
    <div class="col s6 m7 l8">
    <?php
      if (isset($_GET['editSet'])) {
        echo "<button type='submit' name='q_saveAll' class='waves-effect waves-light btn btn-block green'>บันทึก</button>";
      } else {
        echo "<button type='submit' name='q_saveAll' class='waves-effect waves-light btn btn-block blue'>บันทึก</button>";
      }
    ?>
    </div>
    <div class="col s6 m5 l4">
      <a class='waves-effect waves-light btn btn-block grey' href='manage-quiz.php'>ยกเลิก</a>
    </div>
  </div>
  </form>
</div>
<div class="template" id="questionTemplate">
  <div class="row" data-qno="1" data-qtype="v"> <!-- qtype v/g/r/l -->
    <div class="col s12 input-field">
      <h6 class="input-label">โจทย์</h6>
      <input name="v-q-1" id="questionTitle" type="text" required>
    </div>
    <div class="col s6">
      <input class="with-gap" name="v-c-1" type="radio" id="v-c-1-1" value="1" required>
      <label for="v-c-1-1" value="1">ตัวเลือก 1</label>
    </div>
    <div class="col s6">
      <input class="with-gap" name="v-c-1" type="radio" id="v-c-1-2" value="2" required>
      <label for="v-c-1-2" value="2">ตัวเลือก 2</label>
    </div>
    <div class="col s6 input-field"><input id="choice1" class="ansField" name="v-ans-1-1" type="text" data-forchoice="1" required></div>
    <div class="col s6 input-field"><input id="choice2" class="ansField" name="v-ans-1-2" type="text" data-forchoice="2" required></div>
    <div class="col s6">
      <input class="with-gap" name="v-c-1" type="radio" id="v-c-1-3" value="3" required>
      <label for="v-c-1-3" value="3">ตัวเลือก 3</label>
    </div>
    <div class="col s6">
      <input class="with-gap" name="v-c-1" type="radio" id="v-c-1-4" value="4" required>
      <label for="v-c-1-4" value="4">ตัวเลือก 4</label>
    </div>
    <div class="col s6 input-field"><input id="choice3" class="ansField" name="v-ans-1-3" type="text" data-forchoice="3" required></div>
    <div class="col s6 input-field"><input id="choice4" class="ansField" name="v-ans-1-4" type="text" data-forchoice="4" required></div>
    <div class="col s12 tailbutton">
      <button type="button" class="waves-effect waves-light btn red deleteQ" data-deleteqno="1" data-deleteqtype="v">ลบ</button>
    </div>
  </div>
</div>


<script src="js/manage-quiz.js" charset="utf-8"></script>
<?php require_once 'element/footer.php'; ?>
