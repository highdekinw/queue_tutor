<?php
  require_once 'element/header.php';
  
  $sql = "SELECT * FROM course,term WHERE course.type=1 AND course._year=term.year AND course._term=term.term AND term.current=1";
  $res = $mysqli->query($sql);
  $courses = array();
  while($row = $res->fetch_assoc()) {
    $room = $mysqli->query("SELECT room.name FROM room,course WHERE course.id=".$row['id']." AND room.id=course._room")->fetch_assoc();
    $row['rname'] = $room['name'];
    array_push($courses, $row);
  }

  function printPeriod($courses, $period=1, $day_list, $mysqli)
  {
    $subjects_count = $mysqli->query("SELECT COUNT(subject) as count, subject as id FROM course, term WHERE period=1 AND type=1 AND course._year=term.year AND course._term=term.term AND term.current=1 GROUP BY subject");
    $subjects1 = [];
    while($subject = $subjects_count->fetch_assoc()){
      $subject_id = $subject['id'];
      $subjects1[$subject_id] = $subject['count'];
    }
    $subjects_count = $mysqli->query("SELECT COUNT(subject) as count, subject as id FROM course, term WHERE period=2 AND type=1 AND course._year=term.year AND course._term=term.term AND term.current=1 GROUP BY subject");
    $subjects2 = [];
    while($subject = $subjects_count->fetch_assoc()){
      $subject_id = $subject['id'];
      $subjects2[$subject_id] = $subject['count'];
    }
    $COURSE_NAME = ['', 'ENG BASIC', 'ENG 1', 'ENG 2', 'ENG 3', 'ENG 4', 'ENG TOP'];
    echo "<ul class='collapsible' data-collapsible='accordion' style='box-shadow: none; border: none'>";
    for($i = 1; $i<=6; $i++){
      echo "<li>";
      echo "<div class='collapsible-header center ' style='border: none' >
              <div class='waves-effect waves-light btn btn-block '>
                $COURSE_NAME[$i]";
                if( isset($subjects1[$i]) && $period == 1){
                  echo "<span class='badge new right yellow-text text-lighten-2' data-badge-caption='คอร์ส'>{$subjects1[$i]}</span>";
                }else if( isset($subjects2[$i]) && $period == 2){
                  echo "<span class='badge new right yellow-text text-lighten-2' data-badge-caption='คอร์ส'>{$subjects2[$i]}</span>";
                }else {
                  echo "<span class='badge new grey-text text-darken-2' data-badge-caption='ไม่มีคอร์ส'></span>";
                }
              echo"</div>
            </div>";
      echo "<div class='collapsible-body' style='padding-right: 15px; padding-left: 15px;'>";
      echo "<div class='collection '>";
      foreach ($courses as $course){
        if ($course['period'] == $period && $course['subject'] == $i){
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
                    course-start='{$course['date_start']}' 
                    course-end='{$course['date_end']}' 
                    course-room='{$course['rname']}' 
                    course-date='{$dayofcourse}' 
                    course-time='{$course['time_start']} - {$course['time_end']}'
                    course-section='".( ($course['section'] == '1') ? 'รอบปกติ' : 'รอบเสริม' )."'
                  ><input hidden data-price={$course['price']}><b>รายละเอียด</b>: {$course['description']}<br/>"
                  .( ($course['section'] == '1') ? 'รอบปกติ' : 'รอบเสริม' )."<br/>
                  {$dayofcourse}<br/>
                  {$course['time_start']} - {$course['time_end']} น.<br/>
                  {$course['date_start']} - {$course['date_end']}
                  </a>";

        }
      }
      echo "</div>";
      echo "</div>";
      echo "</li>";  
    }
    // <a style='border: none;'  class='collection-item grey-text text-darken-1 course-button'>Alvin</a>
echo "</ul>";
    // foreach ($courses as $course) {
    //   if ($course['period'] == $period) {
    //     $dayofcourse = '';
    //     foreach ($day_list as $day => $fullday) {
    //       if ($course[$day] == 1) {
    //         $dayofcourse = $dayofcourse.$fullday." ";
    //       }
    //     }
    //     echo "<div class='row'>
    //             <div class='col s4'>
    //               <a class='waves-effect waves-light btn btn-block course-button' 
    //                 data-courseid='{$course['id']}' 
    //                 data-period='{$course['period']}' 
    //                 course-start='{$course['date_start']}' 
    //                 course-end='{$course['date_end']}' 
    //                 course-room='{$course['rname']}'
    //               ><input hidden data-price={$course['price']}>{$course['name']}</a>
    //             </div>
    //             <div class='col s8'>
    //               {$dayofcourse}<br/><span>{$course['time_start']} - {$course['time_end']}</span>
    //             </div>
    //           </div>";
    //   }
    // }
  }
?>
<div class="container">
  <h2>คอร์สปกติ</h2>
  <p>นักเรียนจะต้องสมัครเรียนทั้งคอร์ส 1.กันยายน-ตุลาคม และ 2.คอร์สเทอม 2 ซึ่งจะต้องสมัครทั้ง 2 คอร์สนี้ ใน 1 ครั้งการสมัคร</p>
  <div class="row hidden">
    <div class="input-field col s4">
      <h6 class="input-label">สถานะนักเรียน ณ ปัจจุบัน <a href="" style="color: red;">*</a></h6>
    </div>
    <div class="input-field col s4">
    <input id="existing_0" name="existing" type="radio" value="0" checked>
      <label for="existing_0">นักเรียนใหม่</label>
    </div>
    <div class="input-field col s4">
      <input id="existing_1" name="existing" type="radio" value="1">
      <label for="existing_1">นักเรียนเก่า<i class="orange-text text-lighten-2">*ส่วนลด200บาท</i></label>
    </div>
  </div>
  <div class="row">
    <div class="col s12 m6">
      <h5>คอร์ส กันยายน - ตุลาคม</h5>
      <?php
        printPeriod($courses, 1, $day_list, $mysqli);
       ?>
    </div>
    <div class="col s12 m6">
      <h5>คอร์ส เทอม 2</h5>
      <?php
        printPeriod($courses, 2, $day_list, $mysqli);
       ?>
    </div>
  </div>
  <div class="row">
    <div class="col s12 m6" id='period-1'></div>
    <div class="col s12 m6" id='period-2'></div>
  </div>

  <div class='row'>
    <div class='col s12'>
      <h5>กรุณาตรวจสอบข้อมูลก่อนการอัพโหลดหลักฐานการชำระเงิน</h5>
      <p class='courseText1'></p>
      <p class='courseText2'></p>
    </div>
  </div>  
  
  <form class="col s12" action="backend/payment.php" method="post" enctype="multipart/form-data" id="slip-form">
    <hr/>
    <br/>
    <h5>อัพโหลดหลักฐานการชำระเงิน</h5>
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
      *กรุณาอัพโหลดหลักฐานการชำระเงินเพื่อนยืนยันการลงทะเบียนเรียน </br> <a href="" style="color: red;">คลิกปุ่ม CHOOSE SLIP FILE เพื่อเลือกภาพหรือไฟล์ที่ต้องการอัพโหลด</a>
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
    <p></p>
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
<?php
  $sql = "SELECT COUNT(seatOfCourse.id) as count 
          FROM seatOfCourse 
          WHERE seatOfCourse._student='{$_SESSION['userid']}'
          AND _slip IS NOT NULL";
  $res = $mysqli->query($sql);
  $course_reserved = $res->fetch_assoc();
  
  // print_r($course_reserved);
  // echo 1;
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
              echo "<b>ระยะเวลาคอร์ส: </b>{$res['date_start']} - {$res['date_end']}<br/>";
              echo "<b>วันที่เรียน: </b>{$dayofcourse} <br/>";
              echo "<b>เวลาเรียน: </b>{$res['time_start']} - {$res['time_end']}<br/>";
              echo "<b>ห้องเรียน: </b>{$res['roomName']}";
              echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>ที่นั่ง: </b>";
              
              if ( $res['seatName'] == 'S1' ) 
                echo "พิเศษ";
              else if ($res['seatName'] == 'X1' || $res['seatName'] == 'X2') 
                echo str_replace('X', 'เสริม', $res['seatName']);
              else echo "{$res['seatName']}";

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
if (isset($_SESSION['usertype'])) {
  echo '<script src="js/select_course.js"></script>';
} else {
  echo "<script>
    $(function() {
      $('.course-button').on('click', function () {
        $('#loginModal').modal('open');
      });
    });
  </script>";
}
 ?>
<?php 
require_once 'element/footer.php'; ?>
