<?php require_once 'element/header.php'; ?>
<?php
    if(isset($_POST['admineditsave'])) {
      $firstname = $_POST['firstname'];
      $lastname = $_POST['lastname'];
      $nickname = $_POST['nickname'];
      $school = $_POST['school'];
      $phone = $_POST['phone'];
      $class = $_POST['class'];
      $email = $_POST['email'];
      $username = $_POST['username'];
      $password = $_POST['password'];
      $userid = $_POST['userid'];

      $sqlll = "update `user` set firstname='{$firstname}', lastname='{$lastname}', nickname='{$nickname}', school='{$school}', phone='{$phone}', class='{$class}', email='{$email}', password='{$password}' where id='{$userid}'";
      $resss = $mysqli->query($sqlll);
      header('Location: manage-user.php?success=3');
    }
    if (isset($_GET['delete_user'])) {
      $delsql = "UPDATE seatOfCourse set _student=NULL, res_order=0, res_with=0, _slip=NULL where _student={$_GET['delete_user']}";
      $mysqli->query($delsql);
      $delsql = "UPDATE user set inuse=0 where id={$_GET['delete_user']}";
      $mysqli->query($delsql);
      header("Location: manage-user.php");
    }
    if( isset($_GET['page'])) $page = $_GET['page'];
    else $page = 0;
    echo "<input id='current_page' type='hidden' value='{$page}'>";
?>
<div class="container">
  <?php
  if(isset($_GET['user_history'])){
    $sql = "SELECT firstname,lastname FROM user WHERE id={$_GET['user_history']}";
    $userresult = $mysqli->query($sql)->fetch_assoc();
    $firstname = $userresult['firstname'];
    $lastname = $userresult['lastname'];

    echo "<h2>ประวัติสมาชิก <h6>รายะละเอียดการลงทะเบียนของ <b>{$firstname} {$lastname}</b></h6></h2>";
    echo '<div class="row" id="coursebar">
    <div class="input-field col s9">
      <br/>
      <i class="fa fa-search prefix"></i>
      <input id="search_history" placeholder="ค้นหาประวัติการลงทะเบียน..." type="text" required>
    </div>
  </div>';
    echo "<div class='row' id='history'>";
    echo "<table id='history_table' class='bordered highlight' data-userid='{$_GET['user_history']}'>";
    echo '<thead><tr><th>ชื่อ</th><th colspan="4">รายละเอียด</th></tr></thead><tbody>';
    $sql = "SELECT  *,
              seat.name as seatName,
              course.name as courseName,
              course.id as courseid,
              slip.checked
            FROM course, seatOfCourse, seat, slip
            WHERE seatOfCourse._student={$_GET['user_history']}
            AND course.id=seatOfCourse._course
            AND seat.id=seatOfCourse._seat
            AND slip.id=seatOfCourse._slip
            ORDER BY seatOfCourse.res_time ASC";
    $res = $mysqli->query($sql);
    while($reshistory = $res->fetch_assoc()){

      echo "<tr data-restime='{$reshistory['res_time']}' data-courseid='{$reshistory['courseid']}' data-seat='{$reshistory['seatName']}'>";
      echo "<td><b>{$reshistory['courseName']}</b>";
      echo "<td  colspan='1'>ช่วงเวลา: <b>".($reshistory['period'] == 1 ? "กันยายน - ตุลาคม": "เทอม 2")."</b><br/>";
      echo "คอร์ส: <b>".($reshistory['type'] == 1 ? "รอบปกติ": "รอบเสริม")."</b><br/>";
      echo "ห้อง: <b>{$room_list[$reshistory['_room']]}</b><br/>";
      echo "</td>";
      $dayNames = "";
        foreach ($day_list as $day => $fullday) {
          if ($reshistory[$day] == 1) {
            $dayNames = $dayNames.$fullday." ";
          }
        }
      echo "<td colspan='2'>วันที่เรียน: <b>{$dayNames}</b><br/>";
      echo "เวลาเรียน: <b>{$reshistory['time_start']} - {$reshistory['time_end']}</b><br/>
          เริ่มเรียน: <b>{$locale_date($reshistory['date_start'])}</b><br/>
          สิ้นสุด: <b>{$locale_date($reshistory['date_end'])}</b></td>";
      $resTime = array();
      $resTime = explode(" ",$reshistory['res_time']);
      echo "<td colspan='1'>เทอม: <b>";
      echo ($reshistory['period'] == 1 ? "กันยายน - ตุลาคม": "เทอม 2");
      echo "/{$reshistory['_year']}</b><br/>";
      echo "วันที่สมัคร: <b>{$reshistory['res_time']}</b><br/>";
      echo "การชำระเงิน: <b>";
      echo ($reshistory['slipUploaded'] == 0 ? "ยังไม่ชำระเงิน": "เสร็จสิ้น");
      echo "</b>";
      echo "<br/>สถานะ: <b>".($reshistory['checked'] == 1 ? 'ผ่าน' : 'รอดำเนินการ')."</b></td>";

      echo "</tr>";

    }
    echo "</tbody></table></div>";

  }else if (isset($_GET['edit_user'])) {
    $sql = "SELECT * from user where id={$_GET['edit_user']}";
    $res = $mysqli->query($sql);
    $resprofile = $res->fetch_assoc();
    $firstname = $resprofile['firstname'];
    $lastname = $resprofile['lastname'];
    $nickname = $resprofile['nickname'];
    $school = $resprofile['school'];
    $phone = $resprofile['phone'];
    $class = $resprofile['class'];
    $email = $resprofile['email'];
    $username = $resprofile['username'];
    $password = $resprofile['password'];
    $userid = $resprofile['id'];
    echo "<h2>แก้ไขข้อมูลส่วนตัว</h2>";
    echo '<div class="row" id="profile">';
    echo "<form class='col s12' action='manage-user.php' id='usereditform' method='post'>
      <div class='row'>
        <div class='input-field col s6'>
          <h6 class='input-label'>Username</h6>
          <input type='hidden' name='username' value='{$username}'>
          <input type='hidden' name='userid' value='{$userid}'>
          <input id='username' type='text' value='{$username}' disabled>
        </div>
        <div class='input-field col s6'>
          <h6 class='input-label'>Password</h6>
          <input id='password' name='password' type='text' value='{$password}'>
        </div>
        <div class='input-field col s6'>
          <h6 class='input-label'>ชื่อ</h6>
          <input id='firstname' name='firstname' type='text' value='{$firstname}'>
        </div>
        <div class='input-field col s6'>
          <h6 class='input-label'>นามสกุล</h6>
          <input id='lastname' name='lastname' type='text' value='{$lastname}'>
        </div>
        <div class='input-field col s6'>
          <h6 class='input-label'>ชื่อเล่น</h6>
          <input id='nickname' name='nickname' type='text' value='{$nickname}'>
        </div>
        <div class='input-field col s3'>
          <h6 class='input-label'>โรงเรียน</h6>
          <input id='school' name='school' type='text' value='{$school}'>
        </div>
        <div class='input-field col s3'>
          <h6 class='input-label'>ชั้น</h6>
          <input id='class' name='class' type='text' value='{$class}'>
        </div>
        <div class='input-field col s6'>
          <h6 class='input-label'>เบอร์ติดต่อผู้ปกครอง</h6>
          <input id='email' name='email' type='text' value='{$email}'>
        </div>
        <div class='input-field col s6'>
          <h6 class='input-label'>เบอร์โทร</h6>
          <input id='phone' name='phone' type='text' value='{$phone}'>
        </div>
      </div>
      <div class='row'>
        <div class='col s12'>
          <button type='submit' name='admineditsave' class='waves-effect waves-light btn btn-large'>บันทึก</button>
          <a class='waves-effect waves-light btn btn-large' href='manage-user.php'>ยกเลิก</a>
        </div>
      </div>
    </form>";
    echo "</div>";
    echo "<table id='history_table' class='hidden' data-userid='-1'></table>"; // Not history mode
  } else {
    echo "<h2>จัดการสมาชิก</h2>";
    echo '<div class="row" id="userbar">
          <div class="input-field col s6">
            <i class="fa fa-search prefix"></i>
            <input id="search_user" placeholder="ค้นหา..." type="text" class="validate">
          </div>
          <div class="col s6 barBtn" id="adduserbtn">
            <a href="signup.php" class="waves-effect waves-light btn blue"><i class="fa fa-plus left"></i>เพิ่มสมาชิก</a>
          </div></div>';
    echo '<div class="row" id="profile">';
    echo '<table class="bordered highlight">';
    echo '<thead>';
    echo '<tr>';
    echo '<th>ลำดับ</th>';
    echo '<th>ชื่อผู้ใช้</th>';
    echo '<th>ชื่อ</th>';
    echo '<th>นามสกุล</th>';
    echo '<th></th>';
    echo '<th></th>';
    echo '</tr>';
    echo '</thead>';
    echo '<tbody>';
    $sql = "SELECT * from user where status=0 and inuse=1 ORDER BY username ASC";
    $res = $mysqli->query($sql);
    $num = 1;

    while ($resprofile[$num] = $res->fetch_assoc()) {
      $firstname = $resprofile[$num]['firstname'];
      $lastname = $resprofile[$num]['lastname'];
      $nickname = $resprofile[$num]['nickname'];
      $school = $resprofile[$num]['school'];
      $phone = $resprofile[$num]['phone'];
      $class = $resprofile[$num]['class'];
      $email = $resprofile[$num]['email'];
      $username = $resprofile[$num]['username'];
      $password = $resprofile[$num]['password'];
      $userid = $resprofile[$num]['id'];


      echo "<tr data-uid='{$userid}' data-unum='{$num}' unum='{$num}' visible='true' class='hidden'>";
      echo "<td name='num'>{$num}</td>";
      echo "<td>{$username}</td>";
      echo "<td>{$firstname}</td>";
      echo "<td>{$lastname}</td>";
      echo "<td>
      <a href='manage-user.php?edit_user={$userid}' class='waves-effect waves-light btn green'>แก้ไข</a> ";
      // echo "<a class='waves-effect waves-light btn red'href='manage-user.php?delete_user={$userid}'> <i class='fa fa-trash'></i></a></td>";
      echo "<a onClick='deleteUser({$userid}, ";
      echo '"';
      echo $username;
      echo '"';
      echo ")' class='waves-effect waves-light btn red' href='#!'> <i class='fa fa-trash'></i></a>";
      echo "</td>";
      echo"<td>
      <a href='manage-user.php?user_history={$userid}' class='waves-effect waves-light btn orange'>
        <i class='fa fa-clock-o left'></i>ประวัติ
      </a>";
      echo "</tr>";
      $num++;
    }
    echo "</tbody></table></div>";
	  echo "<div class='row' id='pagebar'>";
	  echo '<div class="col s6" id="backbtn">
	  <div class="waves-effect waves-light btn blue"><i class="fa fa-chevron-left left"></i>ย้อนกลับ</div></div>';
	  echo '<div class="col s6 barBtn" id="nextbtn">
	  <div class="waves-effect waves-light btn blue">ถัดไป<i class="fa fa-chevron-right right"></i></div>
	  </div></div>';
    echo "<table id='history_table' class='hidden' data-userid='-1'></table>"; // Not history mode
  }

  ?>
 </div>
</div>
<?php echo "<input id='last_unum' value='{$num}' style='display:none;'></input>"; ?>
<script src="js/manage-user.js"></script>
<?php require_once 'element/footer.php'; ?>
