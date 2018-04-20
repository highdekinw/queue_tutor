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
    global $locale_date;
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
      echo "<div class='collapsible-header center ' style='border: none' >
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
                    course-room='{$course['rname']}'
                    course-date='{$dayofcourse}'
                    course-time='{$course['time_start']} - {$course['time_end']}'
                    course-section='".( ($course['section'] == '1') ? 'รอบปกติ' : 'รอบเสริม' )."'
                  ><input hidden data-price={$course['price']}><b>รายละเอียด</b>: {$course['description']}<br/>"
                  .( ($course['section'] == '1') ? 'รอบปกติ' : 'รอบเสริม' )."<br/>
                  {$dayofcourse}<br/>
                  {$course['time_start']} - {$course['time_end']} น.<br/>
                  {$locale_date($course['date_start'])} - {$locale_date($course['date_end'])}
                  </a>";

        }
      }
    }
    echo "</ul>";
    // foreach ($courses as $course) {
    //   $dayofcourse = '';
    //   foreach ($day_list as $day => $fullday) {
    //     if ($course[$day] == 1) {
    //       $dayofcourse = $dayofcourse.$fullday." ";
    //     }
    //   }
    //   echo "<div class='row'>
    //           <div class='col s4'>
    //             <a class='waves-effect waves-light btn btn-block course-button' data-courseid='{$course['id']}' data-price={$course['price']} data-period='{$course['period']}' course-start='{$course['date_start']}' course-end='{$course['date_end']}' course-room='{$course['rname']}'>{$course['name']}</a>
    //           </div>
    //           <div class='col s8'>
    //             {$dayofcourse}<br/>{$course['time_start']} - {$course['time_end']}
    //           </div>
    //         </div>";
    // }
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
      <p class='courseText'></p>
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
if (isset($_SESSION['usertype'])) {
  echo '<script src="js/select_course_special.js"></script>';
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
<?php require_once 'element/footer.php'; ?>
