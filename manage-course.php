<?php
  require_once 'element/header.php';
  requireUserType(1);

  $sql = "SELECT * FROM term";
  $res = $mysqli->query($sql);
  $terms = array();
  while($term = $res->fetch_assoc()){
    $terms[] = $term;
    if($term['current'] == 1){
      $current = $term;
    }
  }
?>
<div class="container">
  <h2>จัดการคอร์ส</h2>
  <div class="row" id="coursebar">

    <div class="input-field col m4 s6">
      <i class="fa fa-search prefix"></i>
      <input id="search_course" placeholder="ค้นหาคอร์ส..." type="text" required>
    </div>
    <div class="input-field col m2 s3">
      <select name="search_term">
       <option value="0" selected>ดูทั้งหมด</option>
       <?php
        foreach($terms as $term){
          echo "<option value='{$term['term']}-{$term['year']}'>{$term['term']}/{$term['year']}</option>";
        }
       ?>
      </select>
    </div>
    <div class="col m2 s3 barBtn" id="addCourseBtn">
      <a data-target='addCourseModal' class="waves-effect waves-light btn blue btn-block"><i class="fa fa-plus left"></i>เพิ่มคอร์ส</a>
    </div>
    <div class="col m2 s6 barBtn" id="setTermBtn">
      <a data-target='setTermModal' class="waves-effect waves-light btn orange btn-block"><i class="fa fa-cog left"></i>เทอม</a>
    </div>
    <div class="col m2 s6 barBtn" id="addCourseBtn">
      <a href="pending-reserve-time.php"
        target="_blank"
        class="btn btn-block waves-effect waves-light cyan">
        <i class="fa fa-clock-o left"></i> เวลาเริ่ม
      </a>
    </div>
  </div>

  <div class="row" id="courseList">


    <table class="highlight">
      <thead>
        <tr><th style='width: 15%'>ชื่อ</th><th colspan="5" style='width: 85%'>รายละเอียด</th></tr>
      </thead>
      <tbody>
        <?php
        $sql = "SELECT * from course,term WHERE course._year=term.year AND course._term=term.term ORDER BY course.id";
        $res = $mysqli->query($sql);
        while($course = $res->fetch_assoc()) {
          echo "<tr data-courseid='{$course['id']}'  data-term='{$course['term']}-{$course['year']}'>";
          echo "<td><b>{$course['name']}</b></td>";
          echo "<td style='width: 40%'>รายละเอียด: ".$course['description']."<br/>
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
          echo "<td style='width: 20%' colspan='2'>วันที่เรียน: <b>{$dayNames}</b><br/>
          เวลาเรียน: <b>{$course['time_start']} - {$course['time_end']}</b><br/>
          เริ่มเรียน: <b>{$locale_date($course['date_start'])}</b><br/>
          สิ้นสุด: <b>{$locale_date($course['date_end'])}</b><br/>
          เทอม: <b>{$course['_term']}/{$course['_year']}</b>
          </td>";
          echo "<td class='actionBtns'>
          <a class='waves-effect waves-light btn green editCourseBtn' data-editcourseid='{$course['id']}'>แก้ไข</a></td>";
          echo "<td>
          <a class='waves-effect waves-light btn red' href='backend/addCourse.php?delete={$course['id']}'><i class='fa fa-trash'></i></a>
          </td>";
          echo "</tr>";
        }
        ?>
      </tbody>
    </table>
  </div>
</div>

<!-- -------------------- ADD COURSE MODAL ----------------------- -->
<div id="addCourseModal" class="modal modal-fixed-footer">
  <form class="col s12" action="backend/addCourse.php" method="post">
  <div class="modal-content">
    <h5 class="modalTitleCenter">เพิ่มคอร์สใหม่</h5>
      <div class="row">
        <div class='col s12'>
          <h6>ประเภทคอร์ส</h6>
          <div class="input-field col s6">
            <input name="courseType" type="radio" id="courseType1" value='1' checked />
            <label for="courseType1">ธรรมดา</label>
          </div>
          <div class="input-field col s6">
            <input name="courseType" type="radio" id="courseType2" value='2'/>
            <label for="courseType2">พิเศษ</label>
          </div>
          <br/><br/>
        </div>
        <div class="input-field col s12">
          <h6 class="input-label">คอร์ส</h6>
          <input id="courseName" name="courseName" placeholder="ตัวอย่าง: ENGLISH 1" type="hidden" value="Eng Basic" required>
          <select id="course_select" name="course_select" required>
            <option disabled>กรุณาเลือกคอร์ส</option>
            <option value='1'>Eng Basic</option>
            <option value='2'>Eng 1</option>
            <option value='3'>Eng 2</option>
            <option value='4'>Eng 3</option>
            <option value='5'>Eng 4</option>
            <option value='6'>Eng Top</option>
          </select>
        </div>
        <div class ="input-field col s12">
          <h6 class="input-label">รายละเอียด</h6>
          <input id="course_desc" name="course_desc" placeholder="รายละเอียดเพิ่มเติม" type="text">
        </div>
        <!--<div class="input-field col s6">
          <h6 class="input-label">รอบ</h6>
           <select name="courseType">
             <option value="" disabled selected>คลิกเพื่อเลือก</option>
             <option value="1">รอบปกติ</option>
             <option value="2">รอบเสริม</option>
           </select>
         </div>-->
       <div class="input-field col s6">
         <h6 class="input-label">เลือกเทอม</h6>
          <select id="term_add" name="term">
            <option value="" disabled selected>คลิกเพื่อเลือก</option>
            <?php
             foreach($terms as $term){
               echo "<option value='{$term['term']}-{$term['year']}'";
               if($term['current'] == 1){
                 echo "selected";
               }
               echo ">{$term['term']}/{$term['year']}</option>";
             }
            ?>
          </select>
        </div>
        <div class="input-field col s6">
          <h6 class="input-label">ช่วงเวลา<i class="grey-text"> (*ขึ้นอยู่กับเทอมที่เลือก)</i></h6>
          <select id="coursePeriod_add" name="coursePeriod">
            <option value="" disabled selected>คลิกเพื่อเลือก</option>
            <option value="1">กุมภาพันธ์ - มีนาคม</option>
            <option value="2">เทอม 1</option>
            <option value="3">กันยายน - ตุลาคม</option>
            <option value="4">เทอม 2</option>
          </select>
        </div>
        <div class="input-field col s6">
          <h6 class="input-label">รอบ</h6>
          <select name="course_section">
            <option value="" disabled selected>คลิกเพื่อเลือก</option>
            <option value="1">รอบปกติ</option>
            <option value="2">รอบเสริม</option>
          </select>
        </div>
        <div class="input-field col s6 hidden">
          <h6 class="input-label">ราคา</h6>
          <input id="coursePrice" name="coursePrice" placeholder="ตัวอย่าง: 1000" type="number" value="0" required>
        </div>
        <div class="input-field col s6">
          <h6 class="input-label">ห้อง</h6>
          <select name="room">
            <option value="" disabled selected>คลิกเพื่อเลือก</option>
            <?php
             $sql = "select * from room";
             $res = $mysqli->query($sql);
             while($room = $res->fetch_assoc()) {
               echo "<option value='{$room['id']}'>{$locale[$room['name']]}</option>";
             }
            ?>
          </select>
        </div>
        <div class="input-field col s6">
          <h6 class="input-label">วันที่เรียน</h6>
           <select name="day[]" multiple>
             <option value="" disabled selected>คลิกเพื่อเลือก</option>
             <option value="mon">จันทร์</option>
             <option value="tue">อังคาร</option>
             <option value="wed">พุธ</option>
             <option value="thu">พฤหัส</option>
             <option value="fri">ศุกร์</option>
             <option value="sat">เสาร์</option>
             <option value="sun">อาทิตย์</option>
           </select>
         </div>
         <!-- debug with multiple selector -->
         <input type="text" name="mon" class="hidden">
         <input type="text" name="tue" class="hidden">
         <input type="text" name="wed" class="hidden">
         <input type="text" name="thu" class="hidden">
         <input type="text" name="fri" class="hidden">
         <input type="text" name="sat" class="hidden">
         <input type="text" name="sun" class="hidden">

        <div class="input-field col s6">
          <h6 class="input-label">วันที่เริ่มคอร์ส</h6>
          <input id="startDate" name="startDate" type="date" placeholder="คลิกเพื่อเลือกวัน" class="datepicker" required>
        </div>
        <div class="input-field col s6">
          <h6 class="input-label">สิ้นสุดคอร์ส</h6>
          <input id="endDate" name="endDate" type="date" placeholder="คลิกเพื่อเลือกวัน" class="datepicker" required>
        </div>
        <div class="input-field col s3">
           <h6 class="input-label">เริ่มเรียนเวลา</h6>
           <input id="startTime" name="startTime" type="text" class="timepicker" required>
         </div>
         <div class="input-field col s3">
           <h6 class="input-label">ถึง</h6>
           <input id="endTime" name="endTime" type="text" class="timepicker" required>
         </div>
      </div>
  </div>
  <div class="modal-footer">
    <div class="row">
      <div class="col s6"><button type="submit" name="addcourse" class="waves-effect waves-light btn btn-block blue">เพิ่มคอร์ส</button></div>
      <div class="col s6"><a class="waves-effect waves-light btn btn-block modalClose grey">ยกเลิก</a></div>
    </div>
  </div>
  </form>
</div>

<!-- -------------------- EDIT COURSE MODAL ----------------------- -->
<div id="editCourseModal" class="modal modal-fixed-footer">
  <form class="col s12" action="backend/addCourse.php" method="post">
  <div class="modal-content">
    <h5 class="modalTitleCenter">แก้ไขคอร์ส</h5>
      <div class="row">
        <div class='col s12'>
          <h6>ประเภทคอร์ส</h6>
          <div class="input-field col s6">
            <input name="courseType" type="radio" id="courseType1" value='1' disabled/>
            <label for="courseType1">ธรรมดา</label>
          </div>
          <div class="input-field col s6">
            <input name="courseType" type="radio" id="courseType2" value='2' disabled/>
            <label for="courseType2">พิเศษ</label>
          </div>
          <br/><br/>
        </div>
        <div class="input-field col s12">
          <h6 class="input-label">คอร์ส</h6>
          <input id="courseName" name="courseName" placeholder="ตัวอย่าง: ENGLISH 1" type="text" required disabled>
        </div>
        <div class ="input-field col s12">
          <h6 class="input-label">รายละเอียด</h6>
          <input id="course_desc" name="course_desc" placeholder="รายละเอียดเพิ่มเติม" type="text">
        </div>
        <!--<div class="input-field col s6">
          <h6 class="input-label">รอบ</h6>
           <select name="courseType">
             <option value="" disabled selected>คลิกเพื่อเลือก</option>
             <option value="1">รอบปกติ</option>
             <option value="2">รอบเสริม</option>
           </select>
         </div>-->
        <div class="input-field col s6">
          <h6 class="input-label">เลือกเทอม</h6>
          <select id="term_edit" name="term">
            <option value="" disabled selected>คลิกเพื่อเลือก</option>
            <?php
             foreach($terms as $term){
               echo "<option value='{$term['term']}-{$term['year']}'>{$term['term']}/{$term['year']}</option>";
             }
            ?>
          </select>
        </div>
        <div class="input-field col s6">
          <h6 class="input-label">ช่วงเวลา<i class="grey-text"> (*ขึ้นอยู่กับเทอมที่เลือก)</i></h6>
          <select id="coursePeriod_edit" name="coursePeriod">
            <option value="" disabled selected>คลิกเพื่อเลือก</option>
            <option value="1">กุมภาพันธ์ - มีนาคม</option>
            <option value="2">เทอม 1</option>
            <option value="3">กันยายน - ตุลาคม</option>
            <option value="4">เทอม 2</option>
          </select>
        </div>
        <div class="input-field col s6">
          <h6 class="input-label">รอบ</h6>
          <select name="course_section">
            <option value="" disabled selected>คลิกเพื่อเลือก</option>
            <option value="1">รอบปกติ</option>
            <option value="2">รอบเสริม</option>
          </select>
        </div>
        <div class="input-field col s6 hidden">
          <h6 class="input-label">ราคา</h6>
          <input id="coursePrice" name="coursePrice" placeholder="ตัวอย่าง: 1000" type="number" value='0' required>
        </div>
        <div class="input-field col s6">
          <h6 class="input-label">ห้อง</h6>
          <select name="room">
            <option value="" disabled selected>คลิกเพื่อเลือก</option>
            <?php
             $sql = "select * from room";
             $res = $mysqli->query($sql);
             while($room = $res->fetch_assoc()) {
               echo "<option value='{$room['id']}'>{$locale[$room['name']]}</option>";
             }
            ?>
          </select>
        </div>
        <div class="input-field col s6">
          <h6 class="input-label">วันที่เรียน</h6>
           <select name="day[]" multiple>
             <option value="" disabled selected>คลิกเพื่อเลือก</option>
             <option value="mon">จันทร์</option>
             <option value="tue">อังคาร</option>
             <option value="wed">พุธ</option>
             <option value="thu">พฤหัส</option>
             <option value="fri">ศุกร์</option>
             <option value="sat">เสาร์</option>
             <option value="sun">อาทิตย์</option>
           </select>
         </div>
         <!-- debug with multiple selector -->
         <input type="text" name="mon" class="hidden">
         <input type="text" name="tue" class="hidden">
         <input type="text" name="wed" class="hidden">
         <input type="text" name="thu" class="hidden">
         <input type="text" name="fri" class="hidden">
         <input type="text" name="sat" class="hidden">
         <input type="text" name="sun" class="hidden">

        <div class="input-field col s6">
          <h6 class="input-label">วันที่เริ่มคอร์ส</h6>
          <input id="startDate" name="startDate" type="date" placeholder="คลิกเพื่อเลือกวัน" class="datepicker" required>
        </div>
        <div class="input-field col s6">
          <h6 class="input-label">สิ้นสุดคอร์ส</h6>
          <input id="endDate" name="endDate" type="date" placeholder="คลิกเพื่อเลือกวัน" class="datepicker" required>
        </div>
        <div class="input-field col s3">
           <h6 class="input-label">เริ่มเรียนเวลา </h6>
           <input id="startTime" name="startTime" type="text" class="timepicker" required>
         </div>
         <div class="input-field col s3">
           <h6 class="input-label">ถึง</h6>
           <input id="endTime" name="endTime" type="text" class="timepicker" required>
         </div>
      </div>
  </div>
  <div class="modal-footer">
    <div class="row">
      <input type="hidden" name="editid" value="">
      <div class="col s6"><button type="submit" name="editcourse" class="waves-effect waves-light btn btn-block green">บันทึก</button></div>
      <div class="col s6"><a class="waves-effect waves-light btn btn-block modalClose grey">ยกเลิก</a></div>
    </div>
  </div>
  </form>
</div>

<div id="setTermModal" class="modal modal-fixed-footer">
  <form class="col s12" action="backend/setTerm.php" method="post">
  <div class="modal-content">
    <h5 class="modalTitleCenter">ตั้งค่าเทอม</h5>
      <div class="row">
      	<div class="col s12"><h6 class="input-label">เทอมปัจจุบัน: <b><?php echo "{$current['term']}</b>/<b>{$current['year']}"; ?></b></h6></div>
        <div class="col s12"><h6 class="input-label">ตั้งค่าปี</h6></div>
      	<div class="input-field col s8">
      		<select name="year" class="browser-default">
      			<?php
	          		$sql = "SELECT year,SUM(current) as current FROM term GROUP BY year";
	          		$res = $mysqli->query($sql);
	          		$years = array();
	          		while($year = $res->fetch_assoc()){
	          			$years[] = $year;
	          			echo "<option value='{$year['year']}' ";
	          			if($year['current'] == 1){
	          				echo "selected";
	          			}
	          			echo ">{$year['year']}</option>";
	          		}
	          	?>
      		</select>
      	</div>
      	<div class="col s4 " id="addYear">
	      <a class="waves-effect waves-light btn"><i class="fa fa-plus-circle left"></i>เพิ่ม</a>
	    </div>
	    <div class="col s12">
	    	<h6 class="input-label">ตั้งค่าเทอม</h6>
	    </div>
        <div class=" input-field col s6">
          	<input id="term_1" name="term" type="radio" value="1" <?php if($current['term'] == 1){echo "checked";} ?> >
		    <label for="term_1">1 <i>(ต้นปี)</i></label>
		</div>
		<div class=" input-field col s6">
		    <input id="term_2" name="term" type="radio" value="2" <?php if($current['term'] == 2){echo "checked";} ?> >
		    <label for="term_2">2 <i>(ปลายปี)</i></label>
		</div>
      </div>
  </div>
  <div class="modal-footer">
    <div class="row">
      <div class="col s12"><button type="submit" name="setterm" class="waves-effect waves-light btn btn-block blue">ยืนยัน</button></div>
    </div>
  </div>
  </form>
</div>

<script src="js/manage-course.js" charset="utf-8"></script>
<?php require_once 'element/footer.php'; ?>
