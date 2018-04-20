<?php
  require_once 'element/header.php';
  requireUserType(1);
  $user_report = false;
  $course_report = false;
  $search_name = false;
  $search_course = false;
  if(isset($_GET['userid'])){
    $user_report = true;
    $userid = intval($_GET['userid']);

    $sql = "SELECT * FROM user WHERE id='{$userid}'";
    $user = $mysqli->query($sql)->fetch_assoc();

    $sql = "SELECT *,seat.name as seatName, course.name as courseName, course.id as courseid
            FROM course, seatOfCourse, seat
            WHERE seatOfCourse._student={$userid}
            AND course.id=seatOfCourse._course
            AND seat.id=seatOfCourse._seat
            ORDER BY seatOfCourse.res_time ASC";
    $res = $mysqli->query($sql);
  }else if(isset($_GET['courseid'])){
    $course_report = true;
    $courseid = intval($_GET['courseid']);

    $sql = "SELECT * FROM course WHERE id='{$courseid}'";
    $course = $mysqli->query($sql)->fetch_assoc();

    $sql = "SELECT user.username, user.firstname, user.lastname, user.nickname, user.class, seat.name as seatname
            FROM seatOfCourse, user, seat
            WHERE seatOfCourse._course={$courseid}
            AND seatOfCourse._student=user.id
            AND seatOfCourse._seat=seat.id
            ORDER BY seatOfCourse.res_order ASC";
    $users = $mysqli->query($sql);
  }else if(isset($_GET['search_name'])) {
    $search_name = true;

    $sql = "SELECT * from user where status=0 and inuse=1 ORDER BY username ASC";
    $users = $mysqli->query($sql);
  }else {
    $search_course = true;

    $sql = "SELECT * FROM term";
    $terms = $mysqli->query($sql);

    $sql = "SELECT * from course,term WHERE course._year=term.year AND course._term=term.term ORDER BY course.id";
    $courses = $mysqli->query($sql);
  }
?>
<div class="container">
  <h2>จัดการรายงาน</h2>
<?php if($user_report) { ?>
  <p>
    รายงานประวัติการลงทะเบียนของ <b><?php echo $user['username'] ?></b><br/>
    ชื่อ-นามสกุล : <b><?php echo "{$user['firstname']}&ensp;{$user['lastname']}" ?></b> &emsp;
    ชื่อเล่น : <b><?php echo $user['nickname'] ?></b><br/>
    ชั้น : <b><?php echo $user['class'] ?></b> &emsp;
    โรงเรียน : <b><?php echo $user['school'] ?></b><br/>
    เบอร์โทร : <b><?php echo $user['phone'] ?></b><br/>
    เบอร์ติดต่อผู้ปกครอง : <b><?php echo $user['email'] ?></b>
  </p>
  <div class="row">
    <div class="col s12">
      <a class="btn orange" href="./pdf-user-report.php?userid=<?php echo $user['id']; ?>" target="_blank">
        <i class="fa fa-file-pdf-o left"></i>สร้างเอกสาร PDF
      </a>
    </div>
    <table class="bordered highlight">
      <thead>
        <tr>
          <th style='width: 20%'>คอร์ส</th>
          <th style='width: 80%' colspan="4">รายละเอียด</th>
        </tr>
      </thead>
      <tbody>
        <?php
          while($reshistory = $res->fetch_assoc()){
            echo "<tr data-restime='{$reshistory['res_time']}' data-courseid='{$reshistory['courseid']}' data-seat='{$reshistory['seatName']}'></td>";
            echo "<td><b>{$reshistory['courseName']}</b>";
            echo "<td style='width: 20%'>ช่วงเวลา: <b>".($period_name[$reshistory['period']])."</b><br/>";
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
            echo "<td style='width: 20%' colspan='2'>วันที่เรียน: <b>{$dayNames}</b><br/>";
            echo "เวลาเรียน: <b>{$reshistory['time_start']} - {$reshistory['time_end']}</b><br/>
                เริ่มเรียน: <b>{$locale_date($reshistory['date_start'])}</b><br/>
                สิ้นสุด: <b>{$locale_date($reshistory['date_end'])}</b></td>";
            $resTime = array();
            $resTime = explode(" ",$reshistory['res_time']);
            echo "<td style='width: 40%'>";
            echo "รายละเอียด : {$reshistory['description']}<br/>";
            echo "เทอม: <b>";
            echo ($period_name[$reshistory['period']]);
            echo "/{$reshistory['_year']}</b><br/>";
            echo "วันที่สมัคร <b>{$reshistory['res_time']}</b><br/>";
            echo "การชำระเงิน: <b>".($reshistory['slipUploaded'] == 0 ? "ยังไม่ชำระเงิน": "เสร็จสิ้น")."</b>&emsp;&emsp;สถานะ: <b>ผ่าน</b></td>";
            echo "</tr>";
          }
        ?>
        </tbody>
    </table>
  </div>
<?php }else if($course_report){ ?>
  <p>
    รายชื่อนักเรียนที่ลงทะเบียนในคอร์ส <b><?php echo "{$course['name']}" ?></b><br/>
    รายละเอียด : <b><?php echo "{$course['description']}" ?></b><br/>
    คอร์ส : <b><?php echo $course['type'] == 1 ? "ธรรมดา" : "พิเศษ" ?></b> &emsp;
    รอบ : <b><?php echo $course['section'] == 1 ? "ปกติ" : "เสริม" ?></b> &emsp;
    ช่วงเวลา : <b><?php echo $period_name[$course['period']] ?></b><br/>
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
  <div class="row">
    <div class="col s12">
      <a class="btn orange" href="./pdf-course-report.php?courseid=<?php echo $course['id'] ?>" target="_blank"><i class="fa fa-file-pdf-o left"></i>สร้างเอกสาร PDF</a>
    </div>
    <table class="bordered highlight">
      <thead>
        <tr>
          <th>ชื่อผู้ใช้</th>
          <th>ชื่อ - นามสกุล</th>
          <th>ชื่อเล่น</th>
          <th>ชั้น</th>
        </tr>
      </thead>
      <tbody>
      <?php
        while($user = $users->fetch_assoc()){
          echo "<tr>";
          echo "<td>{$user['username']}</td>";
          echo "<td>{$user['firstname']} {$user['lastname']}</td>";
          echo "<td>{$user['nickname']}</td>";
          echo "<td>{$user['class']}</td>";
        //   if ( $user['seatname'] == 'S1' ) echo "<td>พิเศษ</td>";
        //   else if ($user['seatname'] == 'X1' || $user['seatname'] == 'X2')
        //     echo "<td>".str_replace('X', 'เสริม', $user['seatname'])."</td>";
        // else echo "<td>{$user['seatname']}</td>";
          echo "<td></td>";
          echo "</tr>";
        }
      ?>
      </tbody>
    </table>
  </div>

<?php }else if($search_course || $search_name){ ?>
  <div class="row">
    <div class="col s12 m6">
      <input type="radio" name="search_radio" id="radio_search_course" value="1" <?php echo $search_course ? "checked" : ""; ?>/>
      <label for="radio_search_course">ค้นหาจากคอร์ส</label>
    </div>
    <div class="col s12 m6">
      <input type="radio" name="search_radio" id="radio_search_name" value="2" <?php echo $search_name ? "checked" : ""; ?>/>
      <label for="radio_search_name">ค้นหาจากชื่อนักเรียน</label>
    </div>
  </div>
  <div class="row <?php echo !$search_course ? 'hidden' : ''; ?>" id="panel_search_course">
    <div class="col s12 m6 input-field">
      <h6 class="input-label">ชื่อคอร์ส</h6>
      <select id="select_course_name">
        <option value="0" selected>ทั้งหมด</option>
        <option value="Eng Basic">Eng Basic</option>
        <option value="Eng 1">Eng 1</option>
        <option value="Eng 2">Eng 2</option>
        <option value="Eng 3">Eng 3</option>
        <option value="Eng 4">Eng 4</option>
        <option value="Eng Top">Eng Top</option>
        <option value="Eng Reading A">Eng Reading A</option>
        <option value="Eng Reading B">Eng Reading B</option>
        <option value="Eng Ent">Eng Ent</option>
      </select>
    </div>
    <div class="col s12 m6 input-field">
      <h6 class="input-label">เทอม</h6>
      <select id="select_course_term">
        <option value="0">ทั้งหมด</option>
        <?php
          if(isset($terms)){
            while($term = $terms->fetch_assoc()){
              echo "<option value='{$term['term']}-{$term['year']}' ";
              echo $term['current'] == 1 ? 'selected' : '';
              echo ">{$term['term']}/{$term['year']}</option>";
            }
          }
        ?>
      </select>
    </div>
    <div class="col s12 m3 input-field">
      <h6 class="input-label">ช่วงเวลา</h6>
    </div>
    <div class="col s6 m3 input-field">
      <input type="radio" name="period" id="radio_period_0" value="0" checked />
      <label for="radio_period_0">ทั้งหมด</label>
    </div>
    <div class="col s6 m3 input-field">
      <input type="radio" name="period" id="radio_period_1" value="1" />
      <label for="radio_period_1">กันยายน - ตุลาคม</label>
    </div>
    <div class="col s6 m3 input-field">
      <input type="radio" name="period" id="radio_period_2" value="2" />
      <label for="radio_period_2">เทอม 2</label>
    </div>
  </div>
  <div class="row <?php echo !$search_name ? 'hidden' : ''; ?>" id="panel_search_name">
    <div class="col s12 m6 input-field">
      <i class="fa fa-search prefix"></i>
      <input type="text" id="input_search_name" placeholder="ค้นหาจากชื่อนักเรียน..."/>
    </div>
  </div>
  <div class='row'>
  <?php
    if($search_name){
      echo "<table id='users'>";
      echo "<thead>";
      echo "<tr>";
      echo "<th>ลำดับ</th>";
      echo "<th>ชื่อผู้ใช้</th>";
      echo "<th>ชื่อ - นามสกุล</th>";
      echo "<th>ชื่อเล่น</th>";
      echo "<th>ชั้น</th>";
      echo "<th colspan='2'></th>";
      echo "</tr>";
      echo "</thead>";
      echo "<tbody>";
      $i = 1;
      while($user = $users->fetch_assoc()) {
        echo "<tr data-user-number='{$i}'>";
        echo "<td class='skip_search'>{$i}</td>";
        echo "<td>{$user['username']}</td>";
        echo "<td>{$user['firstname']} {$user['lastname']}</td>";
        echo "<td>{$user['nickname']}</td>";
        echo "<td>{$user['class']}</td>";
        echo "<td class='skip_search' colspan='2'><a class='btn light-blue darken-1' href='./manage-report.php?userid={$user['id']}'><i class='fa fa-file-text-o left'></i>รายงาน</a></td>";
        $i = $i + 1;
      }
      echo "</tbody>";
      echo "</table>";

    }else if($search_course){
      echo "<table id='courses' class='highlight'>";
      echo "<thead>";
      echo "<tr>";
      echo "<th style='width: 20%'>ชื่อ</th>";
      echo "<th style='width: 80%' colspan='5'>รายละเอียด</th>";
      echo "</tr>";
      echo "</thead>";
      echo "<tbody>";
      $i = 1;
      while($course = $courses->fetch_assoc()) {
        echo "<tr
          data-courseid='{$course['id']}'
          data-name='{$course['name']}'
          data-term='{$course['term']}-{$course['year']}'
          data-period='{$course['period']}'
          data-course-number='{$i}'
          >";
        echo "<td><b>{$course['name']}</b></td>";
        echo "<td style='width: 30%'>รายละเอียด: ".$course['description']."<br/>
        คอร์ส: <b>".($course['type'] == 1 ? "ธรรมดา" : "พิเศษ")."</b><br/>
        รอบ: <b>".($course['section'] == 1 ? "ปกติ" : "เสริม")."</b><br/>
        ช่วงเวลา: <b>".($period_name[$course['period']])."</b><br/>
        ห้อง: <b>{$room_list[$course['_room']]}</b></td>";
        $dayNames = "";
        foreach ($day_list as $day => $fullday) {
          if ($course[$day] == 1) {
            $dayNames = $dayNames.$fullday." ";
          }
        }
        echo "<td style='width: 30%' colspan='2'>วันที่เรียน: <b>{$dayNames}</b><br/>
        เวลาเรียน: <b>{$course['time_start']} - {$course['time_end']}</b><br/>
        เริ่มเรียน: <b>{$locale_date($course['date_start'])}</b><br/>
        สิ้นสุด: <b>{$locale_date($course['date_end'])}</b><br/>
        เทอม: <b>{$course['_term']}/{$course['_year']}</b>
        </td>";
        echo "<td class='skip_search' colspan='2'><a class='btn light-blue darken-1' href='./manage-report.php?courseid={$course['id']}'><i class='fa fa-file-text-o left'></i>รายงาน</a></td>";
        echo "</tr>";
        $i = $i + 1;
      }
      echo "</tbody>";
      echo "</table>";
    }
  ?>
  </div>
<?php } ?>
</div>
<script src="js/manage-report.js" charset="utf-8"></script>
<?php require_once 'element/footer.php'; ?>
