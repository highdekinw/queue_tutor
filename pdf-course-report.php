<?php
  require_once "backend/setting.php";
  require_once "mpdf/mpdf.php";
  ob_start();
  if(isset($_GET[courseid])){
    $courseid = intval($_GET[courseid]);
    $sql = "SELECT * FROM course WHERE id='{$courseid}'";
    $course = $mysqli->query($sql)->fetch_assoc();

    $sql = "SELECT user.username, user.firstname, user.lastname, user.nickname, user.class, seat.name as seatname
            FROM seatOfCourse, user, seat
            WHERE seatOfCourse._course={$courseid}
            AND seatOfCourse._student=user.id
            AND seatOfCourse._seat=seat.id
            ORDER BY seatOfCourse.res_order ASC";
    $users = $mysqli->query($sql);
?>
<h1 style="font-family: helvetica;">&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&ensp;&ensp;รายงาน</h1>
<p style="font-family: helvetica;">
  รายชื่อนักเรียนที่ลงทะเบียนในคอร์ส <b><?php echo "{$course['name']}" ?></b><br/>
  รายละเอียด : <b><?php echo "{$course['description']}" ?></b><br/>
  คอร์ส : <b><?php echo $course['type'] == 1 ? "ธรรมดา" : "พิเศษ" ?></b> &emsp;
  รอบ : <b><?php echo $course['section'] == 1 ? "ปกติ" : "เสริม" ?></b> &emsp;
  ช่วงเวลา : <b><?php echo $course['period'] == 1 ? "กันยายน - ตุลาคม" : "เทอม 2" ?></b><br/>
  <?php $dayNames = "";
    foreach ($day_list as $day => $fullday) {
      if ($course[$day] == 1) {
        $dayNames = $dayNames.$fullday." ";
      }
    } ?>
  วันที่เรียน : <b><?php echo $dayNames ?></b><br/>
  เริ่มเรียน : <b><?php echo "{$locale_date($course['date_start'])}" ?></b> &emsp;
  สิ้นสุด : <b><?php echo "{$locale_date($course['date_end'])}" ?></b> &emsp;
  เวลา : <b><?php echo "{$course['time_start']}" ?> - <?php echo "{$course['time_end']}" ?></b>
</p>
<table style="font-family: helvetica; width: 100%; border-collapse: collapse">
  <thead>
    <tr>
      <th style="width: 10%; border: 1px solid black; border-right: none; padding: 5px">ชื่อผู้ใช้</th>
      <th style="width: 40%; border: 1px solid black; border-right: none; border-left: none; padding: 5px">ชื่อ - นามสกุล</th>
      <th style="width: 20%; border: 1px solid black; border-right: none; border-left: none; padding: 5px">ชื่อเล่น</th>
      <th style="width: 20%; border: 1px solid black; border-right: none; border-left: none; padding: 5px">ชั้น</th>
    </tr>
  </thead>
  <?php
    while($user = $users->fetch_assoc()){
      echo "<tr>";
      echo "<td style='border: 1px solid black; padding: 10px; text-align: center'>{$user['username']}</td>";
      echo "<td style='border: 1px solid black; padding: 10px; text-align: center'>{$user['firstname']} {$user['lastname']}</td>";
      echo "<td style='border: 1px solid black; padding: 10px; text-align: center'>{$user['nickname']}</td>";
      echo "<td style='border: 1px solid black; padding: 10px; text-align: center'>{$user['class']}</td>";
    }
  ?>
  <tbody>
  </tbody>
</table>

<?php
  }
  $html = ob_get_contents();
  ob_end_clean();
  $pdf = new mPDF('th', 'A4', '0', '');
  $pdf->SetAutoFont();
  $pdf->SetDisplayMode('fullpage');
  $pdf->WriteHTML($html, 2);
  $pdf->Output();
?>
