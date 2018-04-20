<?php
  require_once 'element/header.php';
  $sql = "SELECT * from course,term where type=2 AND course._year=term.year AND course._term=term.term AND term.current=1";
  $res = $mysqli->query($sql);
  $courses = array();
  while($row = $res->fetch_assoc()) {
    $room = $mysqli->query("SELECT room.name FROM room,course WHERE course.id=".$row['id']." AND room.id=course._room")->fetch_assoc();
    $row['rname'] = $room['name'];
    array_push($courses, $row);
  }

  function printSpecial($courses, $day_list, $mysqli){
    global $locale_date, $locale;
    $subjects_count = $mysqli->query("SELECT COUNT(subject) as count, subject as id FROM course, term WHERE type=2 AND course._year=term.year AND course._term=term.term AND term.current=1 GROUP BY subject");
    $subjects = [];
    while($subject = $subjects_count->fetch_assoc()){
      $subject_id = $subject['id'];
      $subjects[$subject_id] = $subject['count'];
    }
    $COURSE_NAME = ['', 'ENG READING A', 'ENG READING B', 'ENG ENT'];
    echo "<ul class='collapsible' data-collapsible='accordion' style='box-shadow: none; border: none'>";
    for($i = 7; $i<=9; $i++){
      echo "<li>";
      echo "<div class='collapsible-header center ' style='border: none' subject-box='1' subject-id='$i'>
              <div class='waves-effect waves-light btn btn-block '>
                {$COURSE_NAME[$i-6]}";
                if( isset($subjects[$i])){
                  echo "<span class='badge new right yellow-text text-lighten-2' data-badge-caption='คอร์ส'>{$subjects[$i]}</span>";
                }else {
                  echo "<span class='badge new grey-text text-darken-2' data-badge-caption='ไม่มีคอร์ส'></span>";
                }
              echo"</div>
            </div>";
      echo "<div class='collapsible-body' style='padding-right: 15px; padding-left: 15px;'>";
      echo "<div class='collection '>";
      foreach ($courses as $course){
        if ($course['subject'] == $i){
          $dayofcourse = '';
          foreach ($day_list as $day => $fullday) {
            if ($course[$day] == 1) {
              $dayofcourse = $dayofcourse.$fullday." ";
            }
          }

          echo "<a href='#!' class='course-button collection-item grey-text text-darken-1'
                    data-course-name='{$course['name']}'
                    data-courseid='{$course['id']}'
                    data-period='{$course['period']}'
                    course-start='{$locale_date($course['date_start'])}'
                    course-end='{$locale_date($course['date_end'])}'
                    course-room='{$locale[$course['rname']]}'
                    course-date='{$dayofcourse}'
                    course-time='{$course['time_start']} - {$course['time_end']}'
                    course-section='".( ($course['section'] == '1') ? 'รอบปกติ' : 'รอบเสริม' )."'
                  ><input hidden data-price={$course['price']}><b>รายละเอียด</b>: {$course['description']}<br/>"
                  .( ($course['section'] == '1') ? 'รอบปกติ' : 'รอบเสริม' )."<br/>
                  {$dayofcourse}<br/>
                  {$course['time_start']} - {$course['time_end']} น.<br/>
                  {$locale_date($course['date_start'])} - {$locale_date($course['date_end'])} <br/>
                  จำนวนนักเรียน: <span student-amount>0</span>/<span max-std-amount>".
                  ($course['rname'] == 'Small' ? 22 : 32 )."</span>
                  </a>";

        }
      }
    }
    echo "</ul>";
  }
?>
<div class="container">
  <h2>คอร์สพิเศษ</h2>
  <p>สมัครเพื่อเรียนคอร์สพิเศษ</p>
  <div class="row">
    <div class="col s12 m8">
      <?php
        printSpecial($courses, $day_list ,$mysqli);
       ?>
    </div>
  </div>
  <div class="row">
    <div class="col s12 m12" id='schedule'></div>
  </div>

  <div class='row'>
    <div class='col s12'>
      <h5>กรุณาตรวจสอบข้อมูลก่อนการอัพโหลดหลักฐานการชำระเงิน</h5>
      <p class='courseText1 hidden'>
        <b>คอร์สพิเศษ : <span id="course_name_1"></span>&nbsp;&nbsp;&nbsp;&nbsp;
        <span id="course_section_1"></span></b><br/>
        <b>ระยะเวลาคอร์ส: </b><span id="course_start_1"></span> - <span id="course_end_1"></span><br/>
        <b>วันที่เรียน: </b><span id="course_date_1"></span>&nbsp;&nbsp;&nbsp;&nbsp;
        <b>ห้องเรียน: </b><span id="course_room_1"></span><br/>
        <b>เวลาเรียน: </b><span id="course_time_1"></span><br/>
        <br/>
      </p>
    </div>
  </div>

  <form class="col s12" action="backend/reserve_seat_special.php" method="post" enctype="multipart/form-data" id="slip-form">
    <hr/>
    <br/>
    <h5>Upload Payment Slip</h5>
    <div class="row">
      <div class="input-field col s12">
        <input type='hidden' name='paymentSelect' >
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
    </div>
    <div class="select-helper hidden"><input type='text' name='helper'></div>
    <h6 id="pre-submit-text" class="col s12 red-text text-lighten-1">
      *กรุณาอัพโหลดหลักฐานการชำระเงินเพื่อนยืนยันการจองที่นั่ง
      <br/>
      <a href="" style="color: red;">คลิกปุ่ม CHOOSE SLIP FILE เพื่อเลือกภาพหรือไฟล์ที่ต้องการอัพโหลด</a>
    </h6>
  </form>

  <div class="col s12 hidden" id="submit-select-course">
    <br/>
    <a class="waves-effect waves-light btn btn-block" id="submit-course">Upload and Register</a>
  </div>
</div>

<div id="reserve_success" class="modal">
  <div class="modal-content">
    <h4>จองสำเร็จ</h4>
    <p id="courseP1"></p>
    <div class="row">
      <div class="col s0 m6"></div>
      <div class="col s12 m3">
        <a href="#!" id="confirm-submit" class=" modal-action modal-close waves-effect waves-light btn btn-block btn-default">Submit</a>
      </div>
      <div class="col s12 m3">
        <a href="#!" id="confirm-cancel" class=" modal-action modal-close waves-effect btn waves-green btn-block grey">Cancel</a>
      </div>
    </div>
  </div>
</div>
<div id="confirm_current" class="modal">
  <div class="modal-content">
    <h5>กรุณาตรวจสอบและยืนยันการลงทะเบียนสาหรับคอร์สนี้</h5>
    <h6>*การลงทะเบียนจะเสร็จสิ้นหลังจากอัพโหลดหลักฐานการชำระเงิน</h6><br/>
    <b>คอร์สพิเศษ : <span id="course_name_confirm"></span>&nbsp;&nbsp;&nbsp;&nbsp;
    <span id="course_section_confirm"></span></b><br/>
    <b>ระยะเวลาคอร์ส: </b><span id="course_start_confirm"></span> - <span id="course_end_confirm"></span><br/>
    <b>วันที่เรียน: </b><span id="course_date_confirm"></span>&nbsp;&nbsp;&nbsp;&nbsp;
    <b>ห้องเรียน: </b><span id="course_room_confirm"></span><br/>
    <b>เวลาเรียน: </b><span id="course_time_confirm"></span><br/>
    <br/>
    <div class="row" style="margin-bottom: 0;">
      <div class="col s0 m6"></div>
      <div class="col s12 m3">
        <a href="#!" id="current-submit" class=" modal-action modal-close waves-effect waves-light btn btn-block btn-default">Submit</a>
      </div>
      <div class="col s12 m3">
        <a href="#!" class=" modal-action modal-close waves-effect btn waves-green btn-block grey">Cancel</a>
      </div>
    </div>
  </div>
</div>
<div id="course_full" class="modal">
  <div class="modal-content">
    <h4>ไม่สามารถเลือกคอร์สนี้ได้</h4>
    <p>คอร์สที่คุณเลือกเต็มแล้ว กรุณาเลือกคอร์สอื่น</p>
      <div class="col s12 m3 barBtn">
        <a href="#!" id="" class=" modal-action modal-close waves-effect waves-light btn">Submit</a>
      </div>
  </div>
</div>
<?php
  $sql = "SELECT COUNT(seatOfCourse.id) as count
          FROM seatOfCourse
          WHERE seatOfCourse._student='{$_SESSION['userid']}'
          AND _slip IS NOT NULL";
  $res = $mysqli->query($sql);
  $course_reserved = $res->fetch_assoc();

  $sql = "SELECT COUNT(seatOfCourse.id) as count
          FROM seatOfCourse, slip
          WHERE _student='{$_SESSION['userid']}'
          AND seatOfCourse._slip IS NOT NULL
          AND slip.id=seatOfCourse._slip
          AND checked=1";
  $res = $mysqli->query($sql);
  $course_checked = $res->fetch_assoc();
  // print_r($course_checked);
  echo "<script>console.log(".( $course_reserved['count'] - $course_checked['count']) .");</script>";
  if ($course_reserved['count'] - $course_checked['count'] > 1){
    echo "<input type='text' hidden value='true' id='cant_select' />";
    $sql = "SELECT  *,
                    seat.name as seatName,
                    course.name as courseName,
                    course.id as courseid,
                    room.name as roomName
            FROM course, seatOfCourse, seat, room, slip
            WHERE seatOfCourse._student={$_SESSION['userid']}
            AND course.id=seatOfCourse._course
            AND seat.id=seatOfCourse._seat
            AND seat._room=course._room
            AND seat._room=room.id
            AND course.type=1
            AND seatOfCourse._slip=slip.id
            AND slip.checked=0
            ORDER BY course.period ASC";
    $result = $mysqli->query($sql);
    echo '<div id="already_reserved" class="modal" value="true">';
      echo '<div class="modal-content">';
        echo "<div class='col s12'>";
          echo "<h5>กรุณารอการตรวจสอบจากทางระบบ!</h5>";
          echo "<h6>คุณได้จองคอร์สและอัพโหลดหลักฐานการชำระเงินเสร็จสิ้น</h6>";
          echo "<div class='row'>";
            while($res = $result->fetch_assoc()){
              echo '<br/>';
              echo "<b>คอร์ส".( $res['period'] == 1 ? 'กันยายน-ตุลาคม' : 'เทอม 2' ).": " ;
              echo $res['courseName'];
              echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;".(res['section'] == 1 ? 'รอบปกติ' : 'รอบเสริม' )."</b></br>";
              echo "<b>ระยะเวลาคอร์ส: </b>{$locale_date($res['date_start'])} - {$locale_date($res['date_end'])}<br/>";
              echo "<b>วันที่เรียน: </b>{$dayofcourse} <br/>";
              echo "<b>เวลาเรียน: </b>{$res['time_start']} - {$res['time_end']}<br/>";
              echo "<b>ห้องเรียน: </b>{$locale[$res['roomName']]}";
              echo '<br/>';
            }
          echo "</div>";
        echo "</div>";
      echo "</div>";
      echo '<div class="modal-footer">';
      echo '<a href="#!" class="modal-action modal-close waves-effect waves-green btn btn-block">ยืนยัน</a>';
      echo '</div>';
    echo "</div>";
  }
?>
<?php
if(!isset($_SESSION['usertype'])){
  echo "<script>
    $(function() {
      $('.course-button').on('click', function () {
        $('#loginModal').modal('open');
      });
    });
  </script>";
}else{
  $sql = "SELECT * FROM pending_time";
  $res = $mysqli->query($sql);
  $res = $res->fetch_assoc();

  if(isset($res['datetime']) && $res['datetime'] > date("Y-m-d H:i:s")){
    echo '<div id="not_in_time" class="modal">
      <div class="modal-content">
        <h5>ยังไม่สามารถลงทะเบียนคอร์สได้ในขณะนี้</h5>
        <h6>จะสามารถลงทะเบียนคอร์สได้ในวันที่ <b>'
        .str_replace( array('-', ' '), array('/', '</b> เวลา <b>'), $res['datetime'] ).'</b></h6>
        <div class="row" style="margin-bottom: 0;">
          <div class="col s0 m9"></div>
          <div class="col s12 m3">
            <a href="#!" class="modal-action modal-close waves-effect btn waves-green btn-block">ตกลง</a>
          </div>
        </div>
      </div>
    </div>';
    echo "<script>
      $(function() {
        $('.course-button').on('click', function () {
          $('#not_in_time').modal('open');
        });
      });
    </script>";
  }else{
    echo '<script src="js/course-selection-special.js"></script>';
  }
}
?>
<?php require_once 'element/footer.php'; ?>
