<?php
  header("Location: ./?error=5"); //render not found
  
  require_once 'element/header.php';
  if (isset($_POST['reservedCourse'])) {
    $target_dir = "upload/";
    $filename = date("YmdHisv").'-'.basename($_FILES["slipUpload"]["name"]);
    $target_file = $target_dir . $filename;
    $fileType = pathinfo($target_file,PATHINFO_EXTENSION);
    if (move_uploaded_file($_FILES["slipUpload"]["tmp_name"], $target_file)) {
        $mysqli->query("INSERT into slip (file, _seat) values ('{$target_file}',{$_POST['reservedCourse']})");
        $mysqli->query("UPDATE seatOfCourse set slipUploaded=1 where id={$_POST['reservedCourse']}");
        header("Location: payment.php?success=2");
      } else {
        header("Location: payment.php?error=2");
      }
  }
  $sql = "SELECT  course.name as courseName,
                  course.price,
                  course.type,
                  course.time_start,
                  course.time_end,
                  course.period,
                  course._year as _year,
                  course._term as _term,
                  seat.name as seatName,
                  seatOfCourse.id,
                  seatOfCourse.existing,
                  room.name as roomName
          from    course, seatOfCourse, seat, room
          where   seatOfCourse._student = {$_SESSION['userid']}
          AND     seatOfCourse._course = course.id
          AND     seatOfCourse._seat = seat.id
          AND     seatOfCourse.slipUploaded = 0
          AND     room.id = course._room";
  $res = $mysqli->query($sql);
?>
<div class="container">
  <h4>Upload payment slip</h4>
  <div class="row" id="addSlip">
   <form class="col s12" action="payment.php" method="post" enctype="multipart/form-data">
     <div class="row">
       <div class="input-field col s12">
         <h6 class="input-label">Your registered courses</h6>
          <select name="reservedCourse">
            <option value="" disabled selected>Choose your option</option>
            <?php
              while ($reservation = $res->fetch_assoc()) {
                $discountText = '';
                // if($reservation['existing'] == 1){
                //   $reservation['price'] -= 200;
                //   $discountText = ' [ราคาพิเศษ]';
                // }
                $reservation['seatName'] = str_replace("X", "เสริม", $reservation['seatName']);
                $reservation['seatName'] = str_replace("S1", "พิเศษ", $reservation['seatName']);
                // echo "<option value='{$reservation['id']}'>{$reservation['courseName']} ".($reservation['type'] == 1 ? "(รอบปกติ":"(รอบเสริม")." {$reservation['_term']}/{$reservation['_year']}) {$reservation['seatName']} - {$reservation['price']} บาท {$discountText}</option>";
                echo "<option value='{$reservation['id']}'>";
                echo "{$reservation['courseName']} ";
                echo $reservation['type'] == 1 ? "(รอบปกติ ":"(รอบเสริม ";
                echo "{$reservation['_term']}/{$reservation['_year']}) / ";
                echo $reservation['period'] == 1 ? "Semester " : "Vacation";
                echo " / เวลา : {$reservation['time_start']} - {$reservation['time_end']} ";
                echo "/ Room : {$reservation['roomName']} ";
                echo "/ Seat : {$reservation['seatName']} ";
                // echo "/ ราคา : {$reservation['price']} บาท {$discountText}";
                echo "</option>";
              }
            ?>
          </select>
        </div>
        <div class="col s12">
          <div class="file-field input-field">
            <div class="btn">
              <span>Choose Slip File</span>
              <input type="file" name="slipUpload" id="slipUpload">
            </div>
            <div class="file-path-wrapper">
              <input class="file-path validate" type="text">
            </div>
          </div>
        </div>
       <div class="col s12">
         <button type="submit" class="waves-effect waves-light btn btn-block blue">Upload</a>
       </div>
     </div>
   </form>
 </div>
</div>
<?php require_once 'element/footer.php'; ?>
