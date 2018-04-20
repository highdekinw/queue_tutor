<?php 
  require_once "backend/setting.php";
  requireUserType(1);
  if(isset($_POST['with_seat'])){
    $mysqli->query("UPDATE course SET period=1 WHERE course._term=2 AND course.period=3;");
    $mysqli->query("UPDATE course SET period=2 WHERE course._term=2 AND course.period=4;");
  }else if(isset($_POST['no_seat'])){
    $mysqli->query("UPDATE course SET period=3 WHERE course._term=2 AND course.period=1;");
    $mysqli->query("UPDATE course SET period=4 WHERE course._term=2 AND course.period=2;");
  }
  
?>
<center>
  <form action="./db_swap.php" method="post">
    <h4>** use only for change database setting **</h4>
    <button type="submit" name="with_seat">แบบเลือกที่นั่ง</button>
    <button type="submit" name="no_seat">แบบไม่เลือกที่นั่ง</button>
  </form>
</center>