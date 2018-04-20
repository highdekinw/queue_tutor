<?php require_once 'element/header.php'; ?>
<?php
    $sql = "SELECT * from user where id={$_SESSION['userid']}";
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

    if(isset($_POST['editprofilesave'])) {
      // print_r($_POST);
      $firstname = $_POST['firstname'];
      $lastname = $_POST['lastname'];
      $nickname = $_POST['nickname'];
      $school = $_POST['school'];
      $phone = $_POST['phone'];
      $class = $_POST['class'];
      $email = $_POST['email'];
      $username = $_POST['username'];
      $password = $_POST['password'];

      $sqlll = "update `user` set firstname='{$firstname}', lastname='{$lastname}', nickname='{$nickname}', school='{$school}', phone='{$phone}', class='{$class}', email='{$email}', password='{$password}' where username='{$username}'";
      $resss = $mysqli->query($sqlll);
      header('Location: profile.php?success=3');
    }
?>
<div class="container">
  <h2>แก้ไขข้อมูลส่วนตัว</h2>
  <div class="row" id="profile">
   <form class="col s12" action="profile.php" id="editprofile" method="post">
     <div class="row">
       <div class="input-field col s6">
         <h6 class="input-label">ชื่อผู้ใช้</h6>
         <input id="username" name="user" type="text" class="validate" disabled value="<?php echo $username; ?>">
         <input type="hidden" name="username" value="<?php echo $username; ?>">
       </div>
       <div class="input-field col s6">
         <h6 class="input-label">รหัสผ่าน <span class='red-text text-lighten-1'>*</span></h6>
         <input id="password" name="password" type="password" value='<?php echo $password; ?>' class="validate" required>
       </div>
       <div class="input-field col s6">
         <h6 class="input-label">ชื่อ <span class='red-text text-lighten-1'>*</span></h6>
         <input id="firstname" name="firstname" type="text" class="validate" value="<?php echo $firstname; ?>" required>
       </div>
       <div class="input-field col s6">
         <h6 class="input-label">นามสกุล <span class='red-text text-lighten-1'>*</span></h6>
         <input id="lastname" name="lastname" type="text" class="validate" value="<?php echo $lastname; ?>" required>
       </div>
       <div class="input-field col s6">
         <h6 class="input-label">ชื่อเล่น <span class='red-text text-lighten-1'>*</span></h6>
         <input id="nickname" name="nickname" type="text" class="validate" value="<?php echo $nickname; ?>" required>
       </div>
       <div class="input-field col s3">
         <h6 class="input-label">โรงเรียน <span class='red-text text-lighten-1'>*</span></h6>
         <input id="school" name="school" type="text" class="validate" value="<?php echo $school; ?>" required>
       </div>
       <div class="input-field col s3">
         <h6 class="input-label">ชั้น <span class='red-text text-lighten-1'>*</span></h6>
         <input id="class" name="class" type="text" class="validate" value="<?php echo $class; ?>" required>
       </div>
       <div class="input-field col s6">
         <h6 class="input-label">E-mail <span class='red-text text-lighten-1'>*</span></h6>
         <input id="email" name="email" type="text" class="validate" value="<?php echo $email; ?>" required>
       </div>
       <div class="input-field col s6">
         <h6 class="input-label">เบอร์โทร <span class='red-text text-lighten-1'>*</span></h6>
         <input id="phone" name="phone" type="text" class="validate" value="<?php echo $phone; ?>" required>
       </div>
     </div>
     <div class="row">
       <div class="col s12">
         <button type="submit" name="editprofilesave" class="waves-effect waves-light btn btn-large">บันทึก</button>
         <a href="./" class="waves-effect waves-light btn btn-large">ยกเลิก</a>
       </div>
     </div>
   </form>
 </div>
</div>
<script>
  $("#signupform").on('submit', function () {
    var valid = true;
    var fields = ['firstname', 'lastname', 'username', 'password', 'school', 'class', 'nickname', 'phone', 'email'];
    for (field of fields) {
      if ($('#' + field).val() == '') {
        valid = false;
      }
    }
    if (!valid) {
      alert('กรุณาใส่ข้อมูลให้ครบถ้วน');
    }
    return valid;
  });
</script>
<?php require_once 'element/footer.php'; ?>
