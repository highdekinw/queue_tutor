<?php
  require_once 'element/header.php';
  requireUserType(1);

  if (isset($_POST['addQuizSet'])) {
    $sql = "INSERT into quiz_set (name, `desc`) values ('{$_POST['q_setName']}', '{$_POST['q_setDesc']}')";
    $res = $mysqli->query($sql);
    $setid = $mysqli->insert_id;
    header("Location: manage-quiz-edit.php?success=7&setid={$setid}");
  }

  if (isset($_GET['deleteSet'])) {
  	$sql = "DELETE FROM quiz_result WHERE _set = {$_GET['deleteSet']}"; //Delete result
  	$res = $mysqli->query($sql);
  	echo mysqli_error($mysqli->query($sql));
  	$sql = "DELETE FROM quiz_question WHERE _set = {$_GET['deleteSet']}"; //Delete question
  	$res = $mysqli->query($sql);
    $sql = "DELETE from quiz_set where id={$_GET['deleteSet']}"; //Delete set
    $res = $mysqli->query($sql);
    header("Location: manage-quiz.php?success=6");
  }

?>
<div class="container">
  <h2>จัดการแบบทดสอบ</h2>
  <div class="row" id="quiz">
    <div class="input-field col s6">
      <i class="fa fa-search prefix"></i>
      <input id="search_quiz" placeholder="ค้นหาแบบทดสอบ..." type="text" required>
    </div>
    <div class="col s6 barBtn" id="addCourseBtn">
      <a class="waves-effect waves-light btn blue" data-target="addQuizSetModal"><i class="fa fa-plus left"></i>เพิ่มชุดแบบทดสอบ</a>
    </div>
  </div>
  <div class="row" id="courseList">
    <table class="highlight">
      <thead>
        <tr><th>ชุดแบบทดสอบ</th><th></th></tr>
      </thead>
      <tbody>
        <?php
          $disp_sql = "SELECT * from quiz_set";
          $disp_res = $mysqli->query($disp_sql);
          $i = 1;
          while ($qset = $disp_res->fetch_assoc()) {
            echo "<tr data-number='{$i}' show='true'>";
            echo "<td class='name-desc'><b>{$qset['name']}</b><br/>{$qset['desc']}</td>";
            echo "<td class='actionBtns'>
            <a class='waves-effect waves-light btn green' href='manage-quiz-edit.php?editSet={$qset['id']}'>แก้ไข</a>
            <a class='waves-effect waves-light btn red' href='manage-quiz.php?deleteSet={$qset['id']}'><i class='fa fa-trash'></i></a></td>";
            echo "</tr>";
            $i = $i + 1;
          }
        ?>
      </tbody>
    </table>
    <br/>
    <div id='back_btn' class='skip_search hidden left btn btn-default blue'>< BACK</div>
    <div id='next_btn' class='skip_search hidden right btn btn-default blue'>NEXT ></div>
  </div>
</div>

<!-- view result as modals -->

<div id="addQuizSetModal" class="modal">
  <form class="col s12" action="manage-quiz.php" method="post">
  <div class="modal-content">
    <h5 class="modalTitleCenter">เพิ่มชุดแบบทดสอบ</h5>
      <div class="row">
        <div class="input-field col s12">
          <h6 class="input-label">ชื่อชุดแบบทดสอบ</h6>
          <input id="q_setName" name="q_setName" type="text" required>
        </div>
        <div class="input-field col s12">
          <h6 class="input-label">รายละเอียด</h6>
           <input id="q_setDesc" name="q_setDesc" type="text" required>
         </div>
      </div>
  </div>
  <div class="modal-footer">
    <div class="row">
      <div class="col s6"><button type="submit" name="addQuizSet" class="waves-effect waves-light btn btn-block blue">เพิ่มโจทย์</button></div>
      <div class="col s6"><a class="waves-effect waves-light btn btn-block modalClose grey">ยกเลิก</a></div>
    </div>
  </div>
  </form>
</div>


<script src="js/manage-quiz.js" charset="utf-8"></script>
<?php require_once 'element/footer.php'; ?>
