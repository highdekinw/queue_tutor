<?php
  require_once 'element/header.php';
  requireUserType(1);

  $course_payment = false;
  $user_payment = false;
  $search_name = false;
  $search_course = false;
  $search_all = false;

  if (isset($_POST['check-reject'])) {
    $slipid = intval($_POST['check-reject']);
    $mysqli->query("UPDATE seatOfCourse set slipUploaded=0, _student=NULL, res_order=0, existing=0, res_with=0,_slip=NULL where _slip=$slipid");
    $mysqli->query("DELETE from slip where id=$slipid");
    $rmfile = $mysqli->query("SELECT file from slip where id=$slipid")->fetch_assoc()['file'];
    // $markunseat = $mysqli->query("SELECT _seat from slip where id=$slipid")->fetch_assoc()['_seat'];
    // $mysqli->query("UPDATE seatOfCourse set slipUploaded=0,_student=NULL,res_order=NULL,existing=0,res_with=0 where id=$markunseat");
    unlink($rmfile);

    header('Location: manage-payment.php');
  } else if (isset($_POST['check-ok'])) {
    $slipid = intval($_POST['check-ok']);
    $mysqli->query("UPDATE slip set checked=1 where id=$slipid");
    header('Location: manage-payment.php');
  }
  if (isset($_GET['search_course'])){
    $search_course = true;
    $sql = "SELECT * FROM term";
    $terms = $mysqli->query($sql);
    $sql = "SELECT * from course,term WHERE course._year=term.year AND course._term=term.term ORDER BY course.id";
    $courses = $mysqli->query($sql);

  } else if (isset($_GET['search_name'])){
    $search_name = true;
    $sql = "SELECT * from user where status=0 and inuse=1 ORDER BY username ASC";
    $users = $mysqli->query($sql);

  } else if (isset($_GET['courseid'])){
    $course_payment = true;
    $courseid = intval($_GET['courseid']);
    $sql = "SELECT * FROM course WHERE id='{$courseid}'";
    $course = $mysqli->query($sql)->fetch_assoc();
    $sql = "SELECT  user.username,
                    user.firstname,
                    user.lastname,
                    user.nickname,
                    user.class,
                    seat.name as seatname,
                    slip.id as slipid,
                    slip.file
            FROM seatOfCourse, user, seat, slip
            WHERE seatOfCourse._course={$courseid}
            AND seatOfCourse._student=user.id
            AND seatOfCourse._seat=seat.id
            AND seatOfCourse._slip=slip.id
            AND slip.checked=0
            ORDER BY user.username ASC";
    $users = $mysqli->query($sql);

  }else if (isset($_GET['userid'])){
    $user_payment = true;
    $userid = intval($_GET['userid']);
    $sql = "SELECT * FROM user WHERE id='{$userid}'";
    $user = $mysqli->query($sql)->fetch_assoc();
    $sql = "SELECT *,
                  seat.name as seatName,
                  course.name as courseName,
                  course.id as courseid,
                  slip.id as slipid
            FROM course, seatOfCourse, seat, slip
            WHERE seatOfCourse._student={$userid}
            AND course.id=seatOfCourse._course
            AND seat.id=seatOfCourse._seat
            AND slip.id=seatOfCourse._slip
            AND slip.checked=0
            ORDER BY seatOfCourse.res_time ASC";
    $res = $mysqli->query($sql);

  } else {
    $search_all = true;

    $sql = 'SELECT    seatOfCourse.res_order,
                      seatOfCourse.res_time,
                      slip.id as slipid,
                      slip.file,
                      user.username,
                      user.firstname,
                      user.lastname,
                      user.class,
                      semester_course.name as semester,
                      vacation_course.name as vacation,
                      semester_course.id as semester_course_id,
                      vacation_course.id as vacation_course_id,
                      vacation_course.type
            FROM      seatOfCourse
            JOIN      slip ON seatOfCourse._slip = slip.id
            JOIN      seatOfCourse v ON seatOfCourse.res_with = v.id
            JOIN      user ON seatOfCourse._student = user.id
            JOIN      course semester_course ON v._course = semester_course.id
            JOIN      course vacation_course ON seatOfCourse._course = vacation_course.id
            WHERE     slip.checked = 0
            GROUP BY  seatOfCourse.res_order
            ORDER BY  seatOfCourse.res_order ASC';
    $res = $mysqli->query($sql);

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

?>
<div class="container">
  <h4>หลักฐานการชำระเงิน</h4>
<?php if($course_payment) { ?>
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
      <a class="btn orange" href="./pdf-course-payment.php?courseid=<?php echo $course['id']; ?>" target="_blank">
        <i class="fa fa-file-pdf-o left"></i>สร้างเอกสาร PDF
      </a>
    </div>
    <form action="manage-payment.php?courseid=<?php echo $course['id'] ?>" method="post">
    <table class="bordered highlight">
      <thead>
        <tr>
          <th>ชื่อผู้ใช้</th>
          <th>ชื่อ - นามสกุล</th>
          <th>ชื่อเล่น</th>
          <th>ชั้น</th>
          <th colspan="3"></th>
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
          // if ( $user['seatname'] == 'S1' ) echo "<td>พิเศษ</td>";
          // else if ($user['seatname'] == 'X1' || $user['seatname'] == 'X2')
          //   echo "<td>".str_replace('X', 'เสริม', $user['seatname'])."</td>";
          // else echo "<td>{$user['seatname']}</td>";
          echo "<td></td>";
          echo "<td class='actionBtns'>";
          echo "<a  target='_blank'
                    class='waves-effect waves-light btn blue'
                    href='show-image.php?img_url={$user['file']}'>
                <i class='fa fa-picture-o left' ></i> ดูรูปภาพ
                </a>";
          echo "</td>";
          echo "<td class='actionBtns'>";
          echo "<button type='submit' name='check-ok' class='waves-effect waves-light btn green' value='{$user['slipid']}'>
                <i class='fa fa-check'></i></button>";
          echo "</td>";
          echo "<td>";
          echo "<button type='submit'
                        onclick=\"javascript: return confirm('ต้องการยกเลิกการชำระเงินของ {$user['username']} ?');\"
                        name='check-reject'
                        class='waves-effect waves-light btn red'
                        value='{$user['slipid']}'>
                <i class='fa fa-times'></i>
                </button>";
          echo "</td>";
          echo "</tr>";
        }
      ?>

      </tbody>
    </table>
  </div>

<?php }else if($user_payment){ ?>
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
      <a class="btn orange" href="./pdf-user-payment.php?userid=<?php echo $user['id']; ?>" target="_blank">
        <i class="fa fa-file-pdf-o left"></i>สร้างเอกสาร PDF
      </a>
    </div>
    <form action="manage-payment.php?courseid=<?php echo $course['id'] ?>" method="post">
    <table class="bordered highlight">
      <thead>
        <tr>
          <th style='width: 15%'>คอร์ส</th>
          <th style='width: 70%' colspan="4">รายละเอียด</th>
          <th colspan="3"></th>
        </tr>
      </thead>
      <tbody>
        <?php
          while($reshistory = $res->fetch_assoc()){
            echo "<tr data-restime='{$reshistory['res_time']}' data-courseid='{$reshistory['courseid']}' data-seat='{$reshistory['seatName']}'></td>";
            echo "<td><b>{$reshistory['courseName']}</b>";
            echo "<td style='width: 15%'>ช่วงเวลา: <b>".($period_name[$reshistory['period']])."</b><br/>";
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
            echo "<td style='width: 15%' colspan='2'>วันที่เรียน: <b>{$dayNames}</b><br/>";
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
            echo "<td class='actionBtns'>";
            echo "<a  target='_blank'
                      class='waves-effect waves-light btn blue'
                      href='show-image.php?img_url={$reshistory['file']}'>
                  <i class='fa fa-picture-o left' ></i> ดูรูปภาพ
                  </a>";
            echo "</td>";
            echo "<td class='actionBtns'>";
            echo "<button type='submit' name='check-ok' class='waves-effect waves-light btn green' value='{$reshistory['slipid']}'>
                  <i class='fa fa-check'></i></button>";
            echo "</td>";
            echo "<td>";
            echo "<button type='submit'
                          onclick=\"javascript: return confirm('ต้องการยกเลิกการชำระเงินของ {$user['username']} ?');\"
                          name='check-reject'
                          class='waves-effect waves-light btn red'
                          value='{$reshistory['slipid']}'>
                  <i class='fa fa-times'></i>
                  </button>";
            echo "</td>";
            echo "</tr>";
          }
        ?>
        </tbody>
    </table>
    </form>
  </div>

<?php }else if($search_course || $search_name || $search_all){ ?>
  <div class="row">
    <div class="col s12 m4">
      <input type="radio" name="search_radio" id="search_radio_all" value="0" <?php echo $search_all ? "checked" : "" ?> />
      <label for="search_radio_all">ทั้งหมด</label>
    </div>
    <div class="col s12 m4">
      <input type="radio" name="search_radio" id="search_radio_course" value="1" <?php echo $search_course ? "checked" : "" ?> />
      <label for="search_radio_course">ค้นหาจากคอร์ส</label>
    </div>
    <div class="col s12 m4">
      <input type="radio" name="search_radio" id="search_radio_name" value="2" <?php echo $search_name ? "checked" : "" ?> />
      <label for="search_radio_name">ค้นหาจากรายชื่อนักเรียน</label>
    </div>
  </div>
  <div class="row <?php echo $search_course ? "" : "hidden" ?>" id="search_by_course">
    <div class="col m6 s12">
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
  <div class="row <?php echo $search_name ? "" : "hidden" ?>" id="search_by_name">
    <div class="input-field col s12 m6">
      <i class="fa fa-search prefix"></i>
      <input type="text" id="name_search" name="search_by_name" placeholder="ค้นหาจากรายชื่อนักเรียน...">
    </div>
  </div>
  <div class="row <?php echo $search_all ? "" : "hidden" ?>" id="search_all">
    <div class="input-field col s12 m6">
      <i class="fa fa-search prefix"></i>
      <input type="text" id="input_search_all" name="input_search_all" placeholder="ค้นหาจาก...">
    </div>
  </div>
  <div class="row">
  <?php if($search_course){
    echo "<table id='courses' class='highlight'>";
    echo "<thead>";
    echo "<tr>";
    echo "<th style='width: 20%'>ชื่อ</th>";
    echo "<th style='width: 80%' colspan='5'>รายละเอียด</th>";
    echo "</tr>";
    echo "</thead>";
    echo "<tbody>";
    while($course = $courses->fetch_assoc()) {
      echo "<tr
        data-courseid='{$course['id']}'
        data-name='{$course['name']}'
        data-term='{$course['term']}-{$course['year']}'
        data-period='{$course['period']}'
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
      echo "<td class='skip_search' colspan='2'>
            <a class='btn' href='./manage-payment.php?courseid={$course['id']}'>
            <i class='fa fa-search left'></i>ดูรายละเอียด
            </a>
            </td>";
      echo "</tr>";
    }
    echo "</tbody>";
    echo "</table>";
  }else if($search_name) {
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
      echo "<tr>";
      echo "<td class='skip_search'>{$i}</td>";
      echo "<td>{$user['username']}</td>";
      echo "<td>{$user['firstname']} {$user['lastname']}</td>";
      echo "<td>{$user['nickname']}</td>";
      echo "<td>{$user['class']}</td>";
      echo "<td class='skip_search' colspan='2'>
            <a class='btn' href='./manage-payment.php?userid={$user['id']}'>
            <i class='fa fa-search left'></i>ดูรายละเอียด
            </a>
            </td>";
      $i = $i + 1;
    }
    echo "</tbody>";
    echo "</table>";

  }else if($search_all) { ?>
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
            if($slip['semester_course_id'] == $slip['vacation_course_id']) $slip['vacation'] = '[คอร์สเสริม]';
            echo "<tr>";
            echo "<td class='username'>{$slip['username']}</td>";
            echo "<td class='res_order'>{$slip['res_order']}</td>";
            echo "<td class='res_time'>{$slip['res_time']}</td>";
            echo "<td class='semester' data-value='{$slip['semester']}'>{$slip['semester']}</td>";
            if($slip['type'] == 1){
              echo "<td class='vacation' data-value='{$slip['vacation']}'>{$slip['vacation']}</td>";
            }else if($slip['type'] == 2){
              echo "<td class='vacation' data-value='{$slip['vacation']}'>[ คอร์สพิเศษ ]</td>";
            }
            echo "<td class='std_name'>{$slip['firstname']} {$slip['lastname']}";
            echo "<td>{$slip['class']}</td>";
            // echo "<td>{$slip['price']}</td>";
            echo "<td class='actionBtns no_search'>";
            echo "<a target='_blank' class='waves-effect waves-light btn blue' href='show-image.php?img_url={$slip['file']}'><i class='fa fa-picture-o left' ></i> ดูรูปภาพ</a>";
            echo "</td>";
            echo "<td class='actionBtns no_search'>";
            echo "<button type='submit' name='check-ok' class='waves-effect waves-light btn green' value='{$slip['slipid']}'>
                  <i class='fa fa-check'></i></button>";
            echo "</td>";
            echo "<td class='no_search'>";
            echo "<button type='submit' onclick=\"javascript: return confirm('ต้องการยกเลิกการชำระเงินของ {$slip['username']} ?');\" name='check-reject' class='waves-effect waves-light btn red' value='{$slip['slipid']}'>
                  <i class='fa fa-times'></i></button>";
            echo "</td>";
            echo "</tr>";
          }
        ?>
      </tbody>
    </table>
    </div>
  </form>
  <?php } ?>
  </div>
<?php } ?>
</div>
<script src="js/manage-payment.js"></script>
<?php require_once 'element/footer.php'; ?>
