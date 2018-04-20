<?php require_once 'element/header.php'; ?>
<?php
  if(isset($_POST) && isset($_POST['username']) && isset($_POST['password'])) {
    $firstname = $_POST['firstname'];
    $lastname = $_POST['lastname'];
    $nickname = $_POST['nickname'];
    $school = $_POST['school'];
    $phone = $_POST['phone'];
    $class = $_POST['class'];
    $email = $_POST['email'];
    $username = $_POST['username'];
    $password = $_POST['password'];
    $existing = $_POST['existing'];

    $res = $mysqli->query("SELECT id FROM user WHERE user.username='{$username}' AND inuse=1");
    if( $r = $res->fetch_assoc())  {
      echo "<script>window.location.replace('signup.php?error=6');</script>";
      // header('Location: signup.php?error=6');
    }else{
      $sql = "insert into user (firstname, lastname, nickname, school, phone, class, email, username, password, existing) values ('$firstname', '$lastname', '$nickname', '$school', '$phone', '$class', '$email', '$username', '$password', '$existing')";
      $res = $mysqli->query($sql);
      if (!res) {
        header('Location: signup.php?error=3');
      } else {
        // header('Location: index.php?success=1');
        if( isset($_SESSION['usertype']) == 1){
          echo "<script>window.location.replace('signup.php?success=8');</script>";
        }
        echo "<script>window.location.replace('index.php?success=1');</script>";
      }
    }
  }
?>
<div class="container">
  <h2>สมัครสมาชิก</h2>
  <div class="row" id="signup">
   <form class="col s12" action="signup.php" id="signupform" method="post">
     <div class="row">
       <div class="input-field col s6">
         <h6 class="input-label">Username <span class='red-text text-lighten-1'>*</span></h6>
         <input id="username" name="username" type="text" class="validate">
       </div>
       <div class="input-field col s6">
         <h6 class="input-label">Password <span class='red-text text-lighten-1'>*</span></h6>
         <input id="password" name="password" type="password" class="validate">
       </div>
       <div class="input-field col s6">
         <h6 class="input-label">ชื่อ <span class='red-text text-lighten-1'>*</span></h6>
         <input id="firstname" name="firstname" type="text" class="validate">
       </div>
       <div class="input-field col s6">
         <h6 class="input-label">นามสกุล <span class='red-text text-lighten-1'>*</span></h6>
         <input id="lastname" name="lastname" type="text" class="validate">
       </div>
       <div class="input-field col s6">
         <h6 class="input-label">ชื่อเล่น <span class='red-text text-lighten-1'>*</span></h6>
         <input id="nickname" name="nickname" type="text" class="validate">
       </div>
       <div class="input-field col s3">
         <h6 class="input-label">โรงเรียน <span class='red-text text-lighten-1'>*</span></h6>
         <input id="school" name="school" type="text" class="validate">
       </div>
       <div class="input-field col s3">
         <h6 class="input-label">ชั้น <span class='red-text text-lighten-1'>*</span></h6>
         <input id="class" name="class" type="text" class="validate">
       </div>
       <div class="input-field col s6">
         <h6 class="input-label">เบอร์ติดต่อผู้ปกครอง <span class='red-text text-lighten-1'>*</span></h6>
         <input id="email" name="email" type="text" class="validate">
       </div>
       <div class="input-field col s6">
         <h6 class="input-label">เบอร์โทร <span class='red-text text-lighten-1'>*</span></h6>
         <input id="phone" name="phone" type="text" class="validate">
       </div>
       <div class="input-field col s4">
         <h6 class="input-label">เคยสมัครเรียนหรือไม่ <span class='red-text text-lighten-1'>*</span></h6>
       </div>
       <div class="input-field col s4">
         <input id="existing_0" name="existing" type="radio" value="0" checked>
         <label for="existing_0">นักเรียนใหม่</label>
       </div>
       <div class="input-field col s4">
         <input id="existing_1" name="existing" type="radio" value="1">
         <label for="existing_1">นักเรียนเก่า</label>
       </div>
     </div>
     <div class="row">
       <div class="col s12">
         <button type="submit" class="waves-effect waves-light btn btn-large">สมัครสมาชิก !</button>
         <a href="./" class="waves-effect waves-light btn btn-large">ยกเลิก</a>
       </div>
     </div>
   </form>
 </div>
</div>
<script>
  $("#signupform").on('submit', function () {
    var valid = true;
    var valid_text = '';
    var fields = ['firstname', 'lastname', 'username', 'password', 'school', 'class', 'nickname', 'phone', 'email'];
    for (field of fields) {
      if ($('#' + field).val().length < 1) {
        valid = false;
      }
    }
    if(!valid){
        valid_text = valid_text + 'กรุณากรอกข้อมูลให้ครบถ้วน \r\n';
      }
    if ($('#username').val().length >= 1 && ! /^[0-9]+$/.test($('#username').val())){
      valid = false;
      valid_text = valid_text + 'username อนุญาติให้ใช้เลข 0-9 เท่านั้น';
    }
    if(!valid){
      alert(valid_text);
    }
    return valid;
  });
</script>
<?php require_once 'element/footer.php'; ?>
