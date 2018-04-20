<?php require_once 'element/header.php'; ?>
<div class="container">
  <h2>ประวัติสมาชิก</h2>
  <div class="row" id="coursebar">
    <div class="input-field col s6">
      <i class="fa fa-search prefix"></i>
      <input id="search_history" placeholder="ค้นหาประวัติ..." type="text" required>
    </div>
  </div>
  <div class='row' id='history'>
  <?php
    echo "<table id='history_table' class='bordered highlight' data-userid='{$_SESSION['userid']}'>";
    echo '<thead><tr><th style="width: 25%">คอร์ส</th><th style="width: 75%" colspan="4">รายละเอียด</th></tr></thead><tbody>';

    $sql = "SELECT *,seat.name as seatName, course.name as courseName, course.id as courseid, slip.checked
            FROM course, seatOfCourse, seat, slip
            WHERE seatOfCourse._student={$_SESSION['userid']}
            AND course.id=seatOfCourse._course
            AND seat.id=seatOfCourse._seat
            AND seatOfCourse._slip=slip.id
            ORDER BY seatOfCourse.res_time ASC";
    $res = $mysqli->query($sql);
    while($reshistory = $res->fetch_assoc()){
      echo "<tr data-restime='{$reshistory['res_time']}' data-courseid='{$reshistory['courseid']}' data-seat='{$reshistory['seatName']}'>";
      echo "<td><b>{$reshistory['courseName']}</b></td>";
      echo "<td style='width: 25%'>ช่วงเวลา: <b>".($reshistory['period'] == 1 ? "กันยายน - ตุลาคม": "เทอม 2")."</b><br/>";
      echo "คอร์ส: <b>".($reshistory['type'] == 1 ? "ธรรมดา": "พิเศษ")."</b><br/>";
      echo "รอบ: <b>".($reshistory['section'] == 1 ? "ปกติ" : "เสริม")."</b><br/>";
      echo "ห้อง: <b>{$room_list[$reshistory['_room']]}</b><br/>";
      echo "</td>";
      $dayNames = "";
        foreach ($day_list as $day => $fullday) {
          if ($reshistory[$day] == 1) {
            $dayNames = $dayNames.$fullday." ";
          }
        }
      echo "<td style='width: 25%' colspan='2'>วันที่เรียน: <b>{$dayNames}</b><br/>";
      echo "เวลาเรียน: <b>{$reshistory['time_start']} - {$reshistory['time_end']}</b><br/>
          เริ่มเรียน: <b>{$locale_date($reshistory['date_start'])}</b><br/>
          สิ้นสุด: <b>{$locale_date($reshistory['date_end'])}</b></td>";
      $resTime = array();
      $resTime = explode(" ",$reshistory['res_time']);
      echo "<td style='width: 25%'>";
      echo "รายละเอียด : <b>{$reshistory['description']}</b><br/>";
      echo "เทอม: <b>";
      echo ($reshistory['period'] == 1 ? "กันยายน - ตุลาคม": "เทอม 2");
      echo "/{$reshistory['_year']}</b><br/>";
      echo "วันที่สมัคร <b>{$reshistory['res_time']}</b><br/>";
      echo "การชำระเงิน: <b>".($reshistory['slipUploaded'] == 0 ? "ยังไม่ชำระเงิน": "เสร็จสิ้น")."</b>";
      echo "<br/>สถานะ: <b>".($reshistory['checked'] == 1 ? 'ผ่าน' : 'รอดำเนินการ')."</b></td>";
      echo "</tr>";
    }
    echo "</tbody></table></div>";
  ?>
</div>
<script src="js/history.js" charset="utf-8"></script>
<?php require_once 'element/footer.php'; ?>
