<?php
  error_reporting(0);

  $dbhost = 'localhost';
  $dbuser = 'root';
  $dbpass = '';
  $dbname = 'test';

  // $dbhost = 'localhost';
  // $dbuser = 'queueedu_school';
  // $dbpass = 's5703081616250';
  // $dbname = 'queueedu_q2';

  $mysqli = new mysqli($dbhost, $dbuser, $dbpass, $dbname);

  if (mysqli_connect_errno()) {
    printf("Connection to database failed\n%s", mysqli_connect_error());
    exit();
  }

  $mysqli->set_charset("utf8");

  date_default_timezone_set('Asia/Bangkok');

  $guest_pages = array(
    'หน้าหลัก' => 'index.php',
    'คอร์ส' => 'courses.php',
    'Summer Course' => 'https://www.facebook.com/summercoursebykrupatandkrubird/',
    'เกี่ยวกับ' => 'about.php'
  );
  $user_pages = array(
    'หน้าหลัก' => 'index.php',
    'คอร์ส' => 'courses.php',
    'Summer Course' => 'https://www.facebook.com/summercoursebykrupatandkrubird/',
    'แบบทดสอบ' => 'quiz.php',
    'เว็บบอร์ด' => 'webboard.php',
    'ดาวน์โหลด' => 'document.php',
    'เกี่ยวกับ' => 'about.php'
  );
  $admin_pages = array(
    'คอร์ส' => 'manage-course.php',
    'แบบทดสอบ' => 'manage-quiz.php',
    'โปรโมชั่น' => 'manage-promo.php',
    'ข่าวสาร' => 'manage-news.php',
    'คลังภาพ' => 'manage-gallery.php',
    'เอกสาร' => 'manage-download.php',
    'การชำระเงิน' => 'manage-payment.php',
    'สมาชิก' => 'manage-user.php',
    'รายงาน' => 'manage-report.php',
    'แดชบอร์ด' => 'manage-dashboard.php'
  );

  $day_list = array(
    'mon' => 'จันทร์',
    'tue' => 'อังคาร',
    'wed' => 'พุธ',
    'thu' => 'พฤหัสบดี',
    'fri' => 'ศุกร์',
    'sat' => 'เสาร์',
    'sun' => 'อาทิตย์'
  );

  $room_list = array(
    1 => 'ห้องเล็ก',
    2 => 'ห้องใหญ่'
  );

  $locale_th = array(
    'January' => 'มกราคม', 
    'February' => 'กุมภาพันธ์', 
    'March' => 'มีนาคม', 
    'April' => 'เมษายน', 
    'May' => 'พฤษภาคม', 
    'June' => 'มิถุนายน', 
    'July' => 'กรกฏาคม', 
    'August' => 'สิงหาคม', 
    'September' => 'กันยายน', 
    'October' => 'ตุลาคม', 
    'November' => 'พฤศจิกายน', 
    'December' => 'ธันวาคม',
    'Large' => 'ห้องใหญ่',
    'Small' => 'ห้องเล็ก'
  );

  $period_name = array(
    "",
    "กุมภาพันธ์ - มีนาคม",
    "เทอม 1",
    "กันยายน - ตุลาคม",
    "เทอม 2"
  );

  $locale_en = array();
  

  function printPages($arrPages, $submenu = false) {
    foreach ($arrPages as $name => $link) {
      if($submenu){
        echo "<li><a href='{$link}'>{$name}</a></li>";
      }else{
        echo "<li><a href='{$link}' style='font-size: 1.25em; font-weight: normal'>{$name}</a></li>";
      }
    }
  }

  function requireUserType($type=0) {
    if (!isset($_SESSION['usertype']) || $_SESSION['usertype'] != $type) { // normal user
      header("Location: index.php");
      die();
    }
  }

  function requireLogin() {
    if (!isset($_SESSION['usertype'])) { // normal user
      header("Location: index.php");
      die();
    }
  }

  foreach ($_POST as $key => $value) {
    $_POST[$key] = filter_var($value, FILTER_SANITIZE_STRING);
  }
  foreach ($_GET as $key => $value) {
    $_GET[$key] = filter_var($value, FILTER_SANITIZE_STRING);
  }

  
  session_start();
  
  // locale
  $_SESION['current_language'] = 'th';
  $locale = array(
    'th' => $locale_th,
    'en' => $locale_en
    )[$_SESION['current_language']];

  $locale_date = function ($date){
    global $locale;
    $date = explode(" ", str_replace(",", "", $date));
    $date[1] = $locale[$date[1]];
    return implode(" ", $date);
  };

?>
