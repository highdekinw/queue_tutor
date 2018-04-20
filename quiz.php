<?php
  require_once 'element/header.php';
  requireLogin();
?>
<div class="container">
  <h2>แบบทดสอบ</h2>
  <p>แบบทดสอบออนไลน์</p>
  <form action="quiz-select-type.php" method="post">
  <select name="quizSet">
    <option value="" disabled selected>คลิกเพื่อเลือกชุดแบบทดสอบ</option>
    <?php
      $quizes = $mysqli->query("SELECT * from quiz_set");
      while ($quiz = $quizes->fetch_assoc()) {
        echo "<option value='{$quiz['id']}'>{$quiz['name']}</option>";
      }
     ?>
  </select>
  <button type="submit" class="waves-effect waves-light btn btn-block orange" name="selectQuizSet">เริ่ม !</button>
  </form>
</div>
<?php require_once 'element/footer.php'; ?>
