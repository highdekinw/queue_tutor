<?php
require_once 'element/header.php';
// Convert Variable Array To Variable
 
while(list($xVarName, $xVarvalue) = each($_GET)) {
     ${$xVarName} = $xVarvalue;
}
 
 
while(list($xVarName, $xVarvalue) = each($_POST)) {
     ${$xVarName} = $xVarvalue;
}
 
while(list($xVarName, $xVarvalue) = each($_FILES)) {
     ${$xVarName."_name"} = $xVarvalue['name'];
     ${$xVarName."_type"} = $xVarvalue['type'];
     ${$xVarName."_size"} = $xVarvalue['size'];
     ${$xVarName."_error"} = $xVarvalue['error'];
     ${$xVarName} = $xVarvalue['tmp_name'];
}
requireLogin();
if (isset($_POST['addThread'])) {
  $currTime = date("Y-m-d H:i:s");
  $mysqli->query("INSERT into webboard_thread (name, content, _user, create_date) values ('{$_POST['threadName']}', '{$_POST['threadContent']}', {$_SESSION['userid']}, '{$currTime}')");
  header('Location: webboard.php');
  //die();

} else if (isset($_POST['editThread'])) {
  $mysqli->query("UPDATE webboard_thread
                  set name='{$_POST['threadName']}', content='{$_POST['threadContent']}'
                  where id={$_POST['thid']}");
  header("Location: webboard.php?viewthread={$_POST['thid']}");
  //die();

} else if (isset($_POST['deleteThread'])) {
  $mysqli->query("DELETE from webboard_thread where id={$_POST['thid']}");

} else if (isset($_POST['addPost'])) {
  $currTime = date("Y-m-d H:i:s");
  $mysqli->query("INSERT into webboard_post (name, content, _user, _thread, create_date) values ('{$_POST['postName']}', '{$_POST['postContent']}', {$_SESSION['userid']}, {$_POST['thid']}, '{$currTime}')");
  header("Location: webboard.php?viewthread={$_POST['thid']}");
  //die();

} else if (isset($_POST['editPost'])) {
  $mysqli->query("UPDATE webboard_post
                  set name='{$_POST['postName']}', content='{$_POST['postContent']}'
                  where id={$_POST['pid']}");
  header("Location: webboard.php?viewthread={$_POST['thid']}");
  //die();
} else if (isset($_POST['deletePost'])) {
  $mysqli->query("DELETE from webboard_post where id={$_POST['pid']}");

}

?>

<div class="container">
  <h3>เว็บบอร์ด</h3>
  <div class="row" id="boardbar">
    <div class="input-field col s6">
      <i class="fa fa-search prefix"></i>
      <?php if (!isset($_GET['viewthread'])): ?>
        <input id="search_board" placeholder="ค้นหากระทู้..." type="text" >
      <?php else: ?>
        <input id="search_thread" placeholder="ค้นหาความคิดเห็น..." type="text">
      <?php endif; ?>
    </div>
    <div class="col s6 barBtn" id="addThreadBtn">
      <?php if (!isset($_GET['viewthread'])): ?>
        <a data-target='addThreadModal' class='waves-effect waves-light btn blue'><i class='fa fa-plus left'></i>เพิ่มกระทู้</a>
      <?php else: ?>
        <a href='webboard.php' class='waves-effect waves-light btn blue'>ย้อนกลับ</a>
      <?php endif; ?>
    </div>
  </div>
  <div class="row" id="board">
    <?php
    if (isset($_GET['viewthread'])) {
      $thid = intval($_GET['viewthread']);
      $thres = $mysqli->query("SELECT webboard_thread.id as wid, user.id as uid, name, content, create_date, update_date, firstname, lastname, username
                                from webboard_thread, user
                                where webboard_thread._user=user.id and webboard_thread.id={$thid}");
      $thread = $thres->fetch_assoc();
      $posts = $mysqli->query("SELECT webboard_post.id as pid, user.id as uid, name, content, create_date, update_date, firstname, lastname, username
                              from webboard_post, user
                              where webboard_post._user=user.id and webboard_post._thread={$thid}");
      echo "<div class='col s12'><div class='card red lighten-4'>";
      echo "<div class='card-content'>";
      echo "<h4 class='card-title'>{$thread['name']}</h4>";
      echo "<h5 class='card-sub-title'>โดย <b>{$thread['firstname']} {$thread['lastname']}</b> เมื่อ <span class='date'>{$thread['create_date']}</span></h5>";
      echo "<p>{$thread['content']}</p>";
      if ($thread['uid'] == $_SESSION['userid']) {
        echo "<div class='postBtn'>
        <a data-target='editThreadModal' class='editThread waves-effect waves-light btn orange'>แก้ไข</a>
        <a class='deleteThread waves-effect waves-light btn red' data-thid='{$thid}'>ลบ</a>
        </div>";
      }
      echo "</div>";
      echo "</div></div>";
      while ($post = $posts->fetch_assoc()) {
        echo "<div class='col s11 offset-s1 tpost' data-text='{$post['name']}'><div class='card red lighten-5'>";
        echo "<div class='card-content'>";
        echo "<h4 class='card-title'>{$post['name']}</h4>";
        echo "<h5 class='card-sub-title'>โดย <b>{$post['firstname']} {$post['lastname']}</b> เมื่อ <span class='date'>{$post['create_date']}</span></h5>";
        echo "<p>{$post['content']}</p>";
        if ($post['uid'] == $_SESSION['userid']) {
          echo "<div class='postBtn'>
          <a class='editPost waves-effect waves-light btn orange' data-pid='{$post['pid']}' data-pname='{$post['name']}' data-pcontent='{$post['content']}'>แก้ไข</a>
          <a class='deletePost waves-effect waves-light btn red' data-pid='{$post['pid']}'>ลบ</a>
          </div>";
        }
        echo "</div>";
        echo "</div></div>";
      }
      echo "<div class='col s12'><a data-target='addPostModal' class='waves-effect waves-light btn btn-block green'>ตอบกลับความคิดเห็น</a></div>";
    } else {
      $threads = $mysqli->query("SELECT webboard_thread.id as wid, user.id as uid, name, create_date, update_date, firstname, lastname, username
                                from webboard_thread, user
                                where webboard_thread._user=user.id
                                ORDER BY webboard_thread.id DESC");
      while ($thread = $threads->fetch_assoc()) {
        echo "<div class='col s12 thread' data-text='{$thread['name']}'><a href='webboard.php?viewthread={$thread['wid']}'><div class='card red lighten-4'>";
        echo "<div class='card-content'>";
        echo "<h4 class='card-title'>{$thread['name']}</h4>";
        echo "<h5 class='card-sub-title'>โดย <b>{$thread['firstname']} {$thread['lastname']}</b> เมื่อ <span class='date'>{$thread['create_date']}</span></h5>";
        echo "</div>";
        echo "</div></a></div>";
      }
    }
    ?>
  </div>
</div>

<div id="addThreadModal" class="modal">
  <form action="webboard.php" method="post">
    <div class="modal-content">
      <h5 class="modalTitleCenter">กระทู้ใหม่</h5>
      <div class="row">
        <div class="input-field col s12">
          <h6 class="input-label">ชื่อกระทู้</h6>
          <input id="threadName" name="threadName" type="text" class="validate" required>
        </div>
        <div class="input-field col s12">
          <h6 class="input-label">ข้อความ</h6>
          <textarea id="newscontent" name="threadContent" class="materialize-textarea" required></textarea>
        </div>
      </div>
    </div>
    <div class="modal-footer row">
      <div class="col s6">
        <button class="waves-effect waves-light btn btn-block blue" type="submit" name="addThread">ยืนยัน</button>
      </div>
      <div class="col s6">
        <a class="waves-effect waves-light btn btn-block modalClose grey">ยกเลิก</a>
      </div>
    </div>
  </form>
</div>

<div id="editThreadModal" class="modal">
  <form action="webboard.php" method="post">
    <?php
    if (isset($_GET['viewthread'])) {
      echo "<input type='hidden' name='thid' value='{$thid}'/>";
    }
     ?>
    <div class="modal-content">
      <h5 class="modalTitleCenter">แก้ไขกระทู้</h5>
      <div class="row">
        <div class="input-field col s12">
          <h6 class="input-label">ชื่อกระทู้</h6>
          <input id="threadName" name="threadName" type="text" class="validate" value="<?php echo $thread['name']; ?>" required>
        </div>
        <div class="input-field col s12">
          <h6 class="input-label">ข้อความ</h6>
          <textarea id="threadContent" name="threadContent" class="materialize-textarea" value="<?php echo $thread['content']; ?>" required></textarea>
        </div>
      </div>
    </div>
    <div class="modal-footer row">
      <div class="col s6">
        <button class="waves-effect waves-light btn btn-block orange" type="submit" name="editThread">แก้ไข</button>
      </div>
      <div class="col s6">
        <a class="waves-effect waves-light btn btn-block modalClose grey">ยกเลิก</a>
      </div>
    </div>
  </form>
</div>

<div id="addPostModal" class="modal">
  <form action="webboard.php" method="post">
    <?php
    if (isset($_GET['viewthread'])) {
      echo "<input type='hidden' name='thid' value='{$thid}'/>";
    }
     ?>
    <div class="modal-content">
      <h5 class="modalTitleCenter">ตอบกลับความคิดเห็น</h5>
      <div class="row">
        <div class="input-field col s12">
          <h6 class="input-label">ชื่อความคิดเห็น</h6>
          <input id="threadName" name="postName" type="text" class="validate" required>
        </div>
        <div class="input-field col s12">
          <h6 class="input-label">ข้อความ</h6>
          <textarea id="newscontent" name="postContent" class="materialize-textarea" required></textarea>
        </div>
      </div>
    </div>
    <div class="modal-footer row">
      <div class="col s6">
        <button class="waves-effect waves-light btn btn-block green" type="submit" name="addPost">ตอบกลับ</button>
      </div>
      <div class="col s6">
        <a class="waves-effect waves-light btn btn-block modalClose grey">ยกเลิก</a>
      </div>
    </div>
  </form>
</div>

<div id="editPostModal" class="modal">
  <form action="webboard.php" method="post">
    <?php
    if (isset($_GET['viewthread'])) {
      echo "<input type='hidden' name='thid' value='{$thid}'/>";
    }
     ?>
     <input type="hidden" name="pid" id="posteditid" value=""/>
    <div class="modal-content">
      <h5 class="modalTitleCenter">แก้ไขความคิดเห็น</h5>
      <div class="row">
        <div class="input-field col s12">
          <h6 class="input-label">ชื่อความคิดเห็น</h6>
          <input id="postEditName" name="postName" type="text" class="validate" required>
        </div>
        <div class="input-field col s12">
          <h6 class="input-label">ข้อความ</h6>
          <textarea id="postEditContent" name="postContent" class="materialize-textarea" required></textarea>
        </div>
      </div>
    </div>
    <div class="modal-footer row">
      <div class="col s6">
        <button class="waves-effect waves-light btn btn-block orange" type="submit" name="editPost">แก้ไข</button>
      </div>
      <div class="col s6">
        <a class="waves-effect waves-light btn btn-block modalClose grey">ยกเลิก</a>
      </div>
    </div>
  </form>
</div>
<script src="js/webboard.js" charset="utf-8"></script>
<?php require_once 'element/footer.php'; ?>
