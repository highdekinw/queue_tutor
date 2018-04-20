<?php
  require_once 'element/header.php';
  requireUserType(1);

  if (isset($_POST['check-reject'])) {
    $slipid = intval($_POST['check-reject']);
    $rmfile = $mysqli->query("SELECT file from slip where id=$slipid")->fetch_assoc()['file'];
    // $markunseat = $mysqli->query("SELECT _seat from slip where id=$slipid")->fetch_assoc()['_seat'];
    // $mysqli->query("UPDATE seatOfCourse set slipUploaded=0,_student=NULL,res_order=NULL,existing=0,res_with=0 where id=$markunseat");
    $mysqli->query("UPDATE seatOfCourse set slipUploaded=0,_student=NULL,res_order=0,existing=0,res_with=0,_slip=NULL where _slip=$slipid");
    unlink($rmfile);
    $mysqli->query("DELETE from slip where id=$slipid");
    header('Location: manage-payment.php');
  } else if (isset($_POST['check-ok'])) {
    $slipid = intval($_POST['check-ok']);
    $mysqli->query("UPDATE slip set checked=1 where id=$slipid");
    header('Location: manage-payment.php');
  }

  // $sql = "SELECT  slip.id as slipid,
  //                 slip.file,
  //                 user.firstname,
  //                 user.lastname,
  //                 user.username,
  //                 user.class,
  //                 course.price,
  //                 course.name as courseName,
  //                 seatOfCourse.existing,
  //                 seatOfCourse.res_time,
  //                 seatOfCourse.res_order
  //         from    course, seatOfCourse, `user`, slip, seat, room
  //         where   seatOfCourse.id = slip._seat
  //         AND     user.id = seatOfCourse._student
  //         AND     course.id = seatOfCourse._course
  //         AND     seat.id = seatOfCourse._seat
  //         AND     slip.checked = 0
  //         GROUP BY seatOfCourse.id
  //         ORDER BY user.username ASC";
  $sql = 'SELECT    seatOfCourse.res_order,
                    seatOfCourse.res_time,
                    slip.id as slipid,
                    slip.file,
                    user.username,
                    user.firstname,
                    user.lastname,
                    user.class,
                    sem.name as semester,
                    vac.name as vacation
          FROM      seatOfCourse
          JOIN      slip ON seatOfCourse._slip = slip.id
          JOIN      seatOfCourse v ON seatOfCourse.res_with = v.id 
          JOIN      user ON seatOfCourse._student = user.id 
          JOIN      course sem ON v._course = sem.id
          JOIN      course vac ON seatOfCourse._course = vac.id
          WHERE     slip.checked = 0
          GROUP BY  seatOfCourse.res_order
          ORDER BY  user.username ASC';
  $res = $mysqli->query($sql);
?>
<div class="container">
  <h4>หลักฐานการชำระเงิน</h4>
  <div class="row">
    <form action="manage-payment.php" method="post">
    <div style="overflow-x:auto">
    <table  id="slips">
      <thead>
        <tr>
          <th>ชื่อผู้ใช้</th>
          <th>ลำดับ</th>
          <th>เวลา</th>
          <th>คอร์ส 1</th>
          <th>คอร์ส 2</th>
          <th>ชื่อ นามสกุล</th>
          <th>ชั้น</th>
          <!--<th>TotalPrice</th>-->
          <th colspan="3"></th>
        </tr>
      </thead>
      <tbody>
        <?php
          while ($slip = $res->fetch_assoc()) {
            // print_r($slip);
            // if($slip['existing'] == 1)$slip['price'] -= 200;
            if($slip['semester'] == $slip['vacation']) $slip['vacation'] = '[คอร์สเสริม]';
            echo "<tr>";
            echo "<td>{$slip['username']}</td>";
            echo "<td>{$slip['res_order']}</td>";
            echo "<td>{$slip['res_time']}</td>";
            echo "<td>{$slip['semester']}</td>";
            echo "<td>{$slip['vacation']}</td>";
            echo "<td>{$slip['firstname']} {$slip['lastname']}";
            echo "<td>{$slip['class']}</td>";
            // echo "<td>{$slip['price']}</td>";
            echo "<td class='actionBtns'>";
            echo "<a class='waves-effect waves-light btn blue' href='show-image.php?img_url={$slip['file']}'><i class='fa fa-picture-o left' ></i> ดูรูปภาพ</a>";
            echo "</td>";
            echo "<td class='actionBtns'>";
            echo "<button type='submit' name='check-ok' class='waves-effect waves-light btn green' value='{$slip['slipid']}'>
                  <i class='fa fa-check'></i></button>";
            echo "</td>";
            echo "<td>";
            echo "<button type='submit' name='check-reject' class='waves-effect waves-light btn red' value='{$slip['slipid']}'>
                  <i class='fa fa-times'></i></button>";
            echo "</td>";
            echo "</tr>";
          }
        ?>
      </tbody>
    </table>
    </div>
  </form>
  </div>
</div>
<?php require_once 'element/footer.php'; ?>
