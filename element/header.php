
<?php
  ob_start();
  require_once 'backend/setting.php';
  $sql = 'SELECT * FROM term WHERE current=1';
  $res = $mysqli->query($sql);
  $t = $res->fetch_assoc();
  $_SESSION['term'] = "{$t['term']}/{$t['year']}";
  echo "<input id='term-tag' value='{$t['term']}/{$t['year']}' hidden>";
?>
<!DOCTYPE html>
<html>
  <head>
    <title> Queue Education</title>
    <link href="http://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link type="text/css" rel="stylesheet" href="css/materialize.min.css"  media="screen,projection"/>
    <link type="text/css" rel="stylesheet" href="css/font-awesome.min.css"/>
    <link type="text/css" rel="stylesheet" href="css/wickedpicker.min.css"/>
    <link type="text/css" rel="stylesheet" href="css/global.css"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1"/>
    <script type="text/javascript" src="js/jquery.js"></script>
    <script type="text/javascript" src="js/wickedpicker.min.js"></script>
    <script type="text/javascript" src="js/materialize.min.js"></script>
    <script type="text/javascript" src="js/global.js"></script>

    <style type="text/css">
      .page-footer {margin-bottom: 0px;}
    </style>
    <?php
      if(isset($_GET)) {
        if (isset($_GET['error'])) {
          switch ($_GET['error']) {
            case 1:
              echo "<script>$(function(){Materialize.toast('ชื่อผู้ใช้หรือรหัสผ่านไม่ถูกต้อง', 5000);});</script>";
              break;
            case 2:
              echo "<script>$(function(){Materialize.toast('อัพโหลดไฟล์ผิดพลาด', 5000);});</script>";
              break;
            case 3:
              echo "<script>$(function(){Materialize.toast('สมัครสมาชิกไม่สำเร็จ กรุณาตรวจสอบข้อมูล', 5000);});</script>";
              break;
            case 4:
              echo "<script>$(function(){Materialize.toast('ไม่สามารถเข้าถึง', 5000);});</script>";
              break;
            case 5:
              echo "<script>$(function(){Materialize.toast('ไม่พบ', 5000);});</script>";
              break;
            case 6:
              echo "<script>$(function(){Materialize.toast('ไม่สำเร็จ! มีชื่อผู้ใช้นี้อยู่ในระบบแล้ว', 5000);});</script>";
              break;
            case 7:
              echo "<script>$(function(){Materialize.toast('ไม่สำเร็จ! คอร์สที่คุณลงทะเบียนเต็มแล้ว', 5000);});</script>";
              break;
            case 8:
              echo "<script>$(function(){Materialize.toast('ไม่สำเร็จ! เกิดข้อผิดพลาดระหว่างลงทะเบียน', 5000);});</script>";
              break;
            case 9:
              echo "<script>$(function(){Materialize.toast('ไม่สำเร็จ! ไม่สามารถลงทะเบียนได้', 5000);});</script>";
              break;
            default:
              # code...
              break;
          }
        } else if (isset($_GET['success'])) {
          switch ($_GET['success']) {
            case 0:
              echo "<script>$(function(){Materialize.toast('สำเร็จ!', 5000)});</script>";
              break;
            case 1:
              echo "<script>$(function(){Materialize.toast('สมัครสมาชิกเสร็จสิ้น กรุณาเข้าสู่ระบบ', 5000)});</script>";
              break;
            case 2:
              echo "<script>$(function(){Materialize.toast('อัพโหลดไฟล์เสร็จสิ้น', 5000)});</script>";
              break;
            case 3:
              echo "<script>$(function(){Materialize.toast('แก้ไขข้อมูลส่วนตัวเสร็จสิ้น!', 5000)});</script>";
              break;
            case 4:
              echo "<script>$(function(){Materialize.toast('บันทึกสำเร็จ!', 5000)});</script>";
              break;
            case 5:
              echo "<script>$(function(){Materialize.toast('เพิ่มสำเร็จ!', 5000)});</script>";
              break;
            case 6:
              echo "<script>$(function(){Materialize.toast('ลบสำเร็จ!', 5000)});</script>";
              break;
            case 7:
              echo "<script>$(function(){Materialize.toast('เพิ่มแบบทดสอบเสร็จสิ้น กรุณาเพิ่มโจทย์', 5000)});</script>";
              break;
            case 8:
              echo "<script>$(function(){Materialize.toast('เพิ่มผู้ใช้เสร็จสิ้น!', 5000)});</script>";
              break;

            default:
              # code...
              break;
          }
        }
      }
    ?>
  </head>
  <body>
    <div class="row">
      <div class="navbar-fixed">
        <?php
        if ($_SESSION['usertype'] == 1) {
          echo '<ul id="adminPages" class="dropdown-content">';
          printPages($admin_pages, true);
          echo '</ul>';
        }
         ?>
        <nav>
          <div class="nav-wrapper">
            <a href="index.php" class="brand-logo"><img src="css/img/logoqueue.jpg"></a>
            <a href="#" data-activates="mobile-demo" class="button-collapse"><i class="material-icons">menu</i></a>
            <ul class="right hide-on-med-and-down">
              <?php
                if (isset($_SESSION['username'])) {
                  printPages($user_pages);
                  if ($_SESSION['usertype'] == 1) {
                    echo '<li><a class="dropdown-button header-menu-text" href="#!" data-activates="adminPages" style="font-size: 1.25em;">จัดการระบบ <i class="fa fa-caret-down"></i></a></li>';
                  }
                  echo "<li><a class='dropdown-button btn' href='#' data-activates='profilemenu'>
                        <img style='width:30px; padding:5px 5px 5px 0px;' src='css/img/user.png' data-pin-nopin='true'>
                        </a><ul id='profilemenu' class='dropdown-content' style='width: 90px; position: absolute; top: 16px; left: 1276px; opacity: 1; display: none;'>
                        <li><a>{$_SESSION['firstname']} {$_SESSION['lastname']}</a></li>
                        <li><a href='profile.php'>ข้อมูลส่วนตัว</a></li>";
                  if ($_SESSION['usertype'] != 1){

                  }
                  echo "<li><a href='history.php'>ประวัติ</a></li>";
                  echo "<li class='divider'></li>
                        <li><a href='backend/logout.php'>ออกจากระบบ</a></li>
                        </ul></li>";
                } else {
                  printPages($guest_pages);
                  echo '<li><a data-target="loginModal" class="waves-effect waves-light btn">เข้าสู่ระบบ</a></li>';
                }
              ?>
            </ul>
          </div>
        </nav>
      </div>
    </div>
    <ul class="side-nav" id="mobile-demo">
      <?php
        if (isset($_SESSION['username'])) {
          echo "<li><a><b>{$_SESSION['firstname']} {$_SESSION['lastname']}</b></a></li>";
          echo "<li><a href='profile.php'>ข้อมูลส่วนตัว</a></li>";
          echo "<li><a href='history.php'>ประวัติ</a></li>";
          if ($_SESSION['usertype'] == 1) {
            printPages($admin_pages, true);
          } else {
            printPages($user_pages, true);
          }
          echo '<li><a href="backend/logout.php">ออกจากระบบ</a></li>';
        } else {
          printPages($guest_pages, true);
          echo "<li><a data-target='loginModal'  id='loginModal-trigger'>Log in</a></li>"; // Login button
        }
      ?>
    </ul>
    <div id="loginModal" class="modal">
      <div class="modal-content">
        <form action="backend/login.php" method="post">
          <div class="row">
            <div class="col s12 m12">
              <h6 class="input-label">ชื่อผู้ใช้</h6>
              <input type="text" name="id" required>
            </div>
            <div class="col s12 m12">
              <h6 class="input-label">รหัสผ่าน</h6>
              <input type="password" name="password" required>
            </div>
            <div class="col s12 m6">
              <button type="submit" class="btn btn-default btn-block blue">เข้าสู่ระบบ</button>
            </div>
            <div class="col s12 m6">
              <a class="btn btn-default btn-block grey" href="signup.php">สมัครสมาชิก</a>
            </div>
          </div>
        </form>
      </div>
    </div>
