<?php
  require_once "backend/setting.php";
  require_once "mpdf/mpdf.php";
  ob_start();
  if(isset($_GET[userid])){
    $userid = intval($_GET[userid]);
    $sql = "SELECT * FROM user WHERE id='{$userid}'";
    $user = $mysqli->query($sql)->fetch_assoc();

    $sql = "SELECT *,seat.name as seatName, course.name as courseName, course.id as courseid
            FROM course, seatOfCourse, seat, slip
            WHERE seatOfCourse._student={$_GET['userid']}
            AND course.id=seatOfCourse._course
            AND seat.id=seatOfCourse._seat
            AND seatOfCourse._slip=slip.id
            AND slip.checked=0
            ORDER BY seatOfCourse.res_time ASC";
    $res = $mysqli->query($sql);
?>
<h1 style="font-family: helvetica;">&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&ensp;การชำระเงิน</h1>
<p style="font-family: helvetica;">
  รายงานประวัติการลงทะเบียนของ <b><?php echo $user['username'] ?></b><br/>
  ชื่อ-นามสกุล : <b><?php echo "{$user['firstname']}&ensp;{$user['lastname']}" ?></b> &emsp;
  ชื่อเล่น : <b><?php echo $user['nickname'] ?></b><br/>
  ชั้น : <b><?php echo $user['class'] ?></b> &emsp;
  โรงเรียน : <b><?php echo $user['school'] ?></b><br/>
  เบอร์โทร : <b><?php echo $user['phone'] ?></b><br/>
  เบอร์ติดต่อผู้ปกครอง : <b><?php echo $user['email'] ?></b>
</p>
<table style="font-family: helvetica; width: 100%; border-collapse: collapse;">
  <thead >
    <tr>
      <th style="width: 25%; border: 1px solid black; border-right: none; padding: 5px">คอร์ส</th>
      <th style="width: 75%; border: 1px solid black; border-left: none; padding: 5px" colspan="2">รายละเอียด</th>
    </tr>
  </thead>
  <tbody>
    <?php
      while($reshistory = $res->fetch_assoc()){
        echo "<tr>";
        echo "<td style='border: 1px solid black; padding: 15px;'>";
        echo "<b>{$reshistory['courseName']}</b>";
        echo "</td>";
        echo "<td style='border: 1px solid black; border-right: none; padding: 10px; width: 35%'>";
        echo "ช่วงเวลา: <b>".($reshistory['period'] == 1 ? "กันยายน - ตุลาคม": "เทอม 2")."</b><br/>";
        echo "คอร์ส: <b>".($reshistory['type'] == 1 ? "ธรรมดา": "พิเศษ")."</b><br/>";
        echo "รอบ: <b>".($reshistory['section'] == 1 ? "ปกติ" : "เสริม")."</b><br/>";
        echo "ห้อง: <b>{$room_list[$reshistory['_room']]}</b><br/>";
        echo "<br/>";
        echo "เริ่มเรียน: <b>{$locale_date($reshistory['date_start'])}</b><br/>";
        echo "สิ้นสุด: <b>{$locale_date($reshistory['date_end'])}</b><br/>";
        $dayNames = "";
        foreach ($day_list as $day => $fullday) {
          if ($reshistory[$day] == 1) {
            $dayNames = $dayNames.$fullday." ";
          }
        }
        echo "วันที่เรียน: <b>{$dayNames}</b><br/>";
        echo "</td>";
        echo "<td style='border: 1px solid black; border-left: none; padding: 10px; width: 40%'>";
        echo "เวลาเรียน: <b>{$reshistory['time_start']} - {$reshistory['time_end']}</b><br/>";
        echo "เทอม: <b>";
        echo ($reshistory['period'] == 1 ? "กันยายน - ตุลาคม": "เทอม 2");
        echo "/{$reshistory['_year']}</b><br/>";
        echo "วันที่สมัคร <b>{$reshistory['res_time']}</b><br/>";
        echo "การชำระเงิน: <b>".($reshistory['slipUploaded'] == 0 ? "ยังไม่ชำระเงิน": "เสร็จสิ้น")."</b><br/>";
        echo "สถานะ: <b>ผ่าน</b><br/>";
        echo "รายละเอียด : {$reshistory['description']}<br/>";
        echo "</td>";
        echo "</tr>";
      }
    ?>
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
