<?php
  require_once 'element/header.php';
  if(isset($_POST['delete'])){
    $mysqli->query("DELETE FROM pending_time");
    header("Location: ./pending-reserve-time.php");
  }else if(isset($_POST['date']) && isset($_POST['time'])){
    $sql = "SELECT * FROM pending_time";
    $res = $mysqli->query($sql);
    $res = $res->fetch_assoc();

    if(!isset($res)){
      $date = date('Y-m-d', strtotime($_POST['date']));
      $time = str_replace(' ','',$_POST['time']);
      $mysqli->query("INSERT INTO pending_time(datetime) VALUES ('{$date} {$time}:00')");
      header("Location: ./pending-reserve-time.php");
    }
  }
?>

<div class="container">
  <h4>ตั้งค่าวันที่ - เวลาการเปิดให้เริ่มสมัครคอร์ส</h4>
  <div class="row">
<?php
  $sql = "SELECT * FROM pending_time";
  $res = $mysqli->query($sql);
  $res = $res->fetch_assoc();

  if(isset($res)){
?>
  <div class="col s12">
    <b>ตั้งค่าเวลาแล้ว</b>
  </div>
  <div class="col s9">ผู้ใช้จะสามารถสมัครเรียนคอร์สได้ในวันที่
    <?php echo str_replace( array('-', ' '), array('/', ' เวลา '), $res['datetime'] ); ?>
  </div>
  <div class="col s3">
    <form action="./pending-reserve-time.php" method="post">
      <button type="submit" name="delete" class="btn red"><i class="fa fa-trash-o"></i></button>
    </form>
  </div>

<?php }else{ ?>
  <div class="col s12">
    <span>*ยังไม่ได้ตั้งค่าเวลา เมื่อตั้งค่าวันที่ - เวลาแล้วผู้ใช้จะไม่สามารถลงทะเบียนคอร์สได้จนกว่าจะถึงเวลาที่ตั้งตั้งไว้</span>
  </div>
  <form id="pending_form" action="./pending-reserve-time.php" method="post">
    <div class="col m6 s12">
      <h6 class="input-label">วันที่</h6>
      <input type="text" class="datepicker" name="date" id="date" placeholder="คลิกเพื่อเลือกวันที่" required>
    </div>
    <div class="col m6 s12">
      <h6 class="input-label">เวลา</h6>
      <input name="time" type="text" class="timepicker hasWickedpicker" required="" onkeypress="return false;" aria-showingpicker="false" tabindex="0" required>
    </div>
  </form>
  <div class="col s12">
    <a href="#" id="pending_confirm" class='btn btn-block'>ยืนยัน</a>
  </div>
  <script>
  $('.timepicker').wickedpicker({
    now: "09:00",
    twentyFour: true,
    hoverState: 'hover-state', //The hover state class to use, for custom CSS
    title: 'เลือกเวลา',
    minutesInterval: 15
  });
  $('.datepicker').pickadate({
    selectMonths: true, // Creates a dropdown to control month
    selectYears: 15, // Creates a dropdown of 15 years to control year,
    today: 'Today',
    clear: 'Clear',
    close: 'Ok',
    closeOnSelect: false // Close upon selecting a date,
  });
  $('#pending_confirm').on('click', function(){
    var date = $('input.timepicker').val();
    var time = $('input.datepicker').val();
    if(date == '' || time == ''){
      alert("กรุณาเลือกวันที่และเวลาให้ครบถ้วน");
    }else{
      $('#pending_form').submit();
    }
  })
  </script>
<?php } ?>
  </div>
</div>

<?php require_once 'element/footer.php'; ?>
