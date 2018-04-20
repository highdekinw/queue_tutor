<?php
  // ob_start();
  require_once 'element/header.php';
  requireUserType(1);

  if (isset($_POST['addnewpro'])) {
    if( strpos($_POST['url'],'http://') > -1 || strpos($_POST['url'],'https://') > -1){
    }else $_POST['url'] ='http://'.$_POST['url'];
    $target_dir = "upload/";
    $filename = date("YmdHisv").'-'.basename($_FILES["promoUpload"]["name"]);
    $target_file = $target_dir . $filename;
    $fileType = pathinfo($target_file,PATHINFO_EXTENSION);
    if (move_uploaded_file($_FILES["promoUpload"]["tmp_name"], $target_file)) {
      $mysqli->query("insert into promotion (img, name, url) values ('{$target_file}','{$_POST['promoname']}','{$_POST['url']}')");
      header("Location: manage-promo.php?success=2");
    } else {
      header("Location: manage-promo.php?error=2");
    }
  }
  
  if (isset($_GET['delete_pro'])) {
    $rmfilesql = "SELECT * from promotion where id={$_GET['delete_pro']}";
    $rmfileres = $mysqli->query($rmfilesql);
    $file = $rmfileres->fetch_assoc();
    unlink($file['img']);
    $sql = "DELETE from promotion where id={$_GET['delete_pro']}";
    $res = $mysqli->query($sql);
  }else if (isset($_POST['editpro'])) {
    if( strpos($_POST['url'],'http://') > -1 || strpos($_POST['url'],'https://') > -1){
    }else $_POST['url'] ='http://'.$_POST['url'];
    $sql = "UPDATE promotion set name='{$_POST['promoname']}',url='{$_POST['url']}' where id={$_POST['editid']}";
    $res = $mysqli->query($sql);
    if ($_FILES['promoUpload']['error'] == 0) { // if file changes
      $rmfilesql = "SELECT * from promotion where id={$_POST['editid']}";
      $rmfileres = $mysqli->query($rmfilesql);
      $file = $rmfileres->fetch_assoc();
      unlink($file['img']);

      $target_dir = "upload/";
      $filename = date("YmdHisv").'-'.basename($_FILES["promoUpload"]["name"]);
      $target_file = $target_dir . $filename;
      $fileType = pathinfo($target_file,PATHINFO_EXTENSION);
      if (move_uploaded_file($_FILES["promoUpload"]["tmp_name"], $target_file)) {
        $mysqli->query("UPDATE promotion set img='{$target_file}' where id={$_POST['editid']}");
      } else {
        header("Location: manage-promo.php?error=2");
      }
    }
    header("Location: manage-promo.php?success=4");
  }
?>
<div class="container">
  <h4>โปรโมชั่น</h4>
  <div class="row" id="promobar">
    <div class="input-field col s6">
      <i class="fa fa-search prefix"></i>
      <input id="search_promo" placeholder="ค้นหาโปรโมชั่น..." type="text" class="validate">
    </div>
    <div class="col s6 barBtn" id="addPromoBtn">
      <a data-target='addPromoModal' class="waves-effect waves-light btn blue"><i class="fa fa-plus left"></i>เพิ่มโปรโมชั่น</a>
    </div>
  </div>
  <div class="row">
    <div class="col s12">
      <table class="highlight">
        <thead>
          <tr>
            <td>โปรโมชั่น</td>
            <td>รูปภาพ</td>
            <td>ลิงค์</td>
            <td></td>
            <td></td>
          </tr>
        </thead>
        <tbody>
          <?php
            $prosql = "select * from promotion";
            $prores = $mysqli->query($prosql);
            $i = 1;
            while ($promo = $prores->fetch_assoc()) {
              echo "<tr data-promoname='{$promo['name']}' data-number='{$i}'>
              <td >{$promo['name']}</td>
              <td><img src='{$promo['img']}' style='max-widht: 50px; max-height: 50px;'></img></td>
              <td colspan='2'><a href='{$promo['url']}'>{$promo['url']}</a></td>
              <td class='actionBtn'><a class='waves-effect waves-light btn editPromo green' data-editpromoid='{$promo['id']}' data-originalfile='{$promo['img']}' data-url='{$promo['url']}'>แก้ไข</a></td>
              <td><a class='waves-effect waves-light btn red' href='manage-promo.php?delete_pro={$promo['id']}'><i class='fa fa-trash'></i></a></td></tr>";
              $i = $i+1;
            }
          ?>
        </tbody>
      </table>
      <br/>
      <div id='back_btn' class='skip_search hidden left btn btn-default blue'>< BACK</div>
      <div id='next_btn' class='skip_search hidden right btn btn-default blue'>NEXT ></div>
    </div>
  </div>
</div>

<div id="addPromoModal" class="modal">
  <div class="modal-content">
    <form action="manage-promo.php" method="post" enctype="multipart/form-data">
      <h5 class="modalTitleCenter">เพิ่มโปรโมชั่นใหม่</h5>
      <div class="row">
        <div class="input-field col s12 m12 l6">
          <h6 class="input-label">ชื่อ</h6>
          <input name="promoname" type="text" class="validate" required>
        </div>
        <div class="col s12 m12 l6 file-field filefield input-field">
          <div class="btn">
            <span>เลือกรูปภาพ</span>
            <input type="file" name="promoUpload" accept="image/*" />
          </div>
          <div class="file-path-wrapper">
            <input class="file-path validate" type="text">
          </div>
        </div>
        <div class="col s12 input-field">
          <h6 class='input-label'>ลิงค์</h6>
          <input name="url" type="text">
        </div>
      </div>
      <div class="row">
        <div class="col s6">
          <button class="waves-effect waves-light btn btn-block blue" type="submit" name="addnewpro">เพิ่ม</button>
        </div>
        <div class="col s6">
          <a class="waves-effect waves-light btn btn-block modalClose grey">ยกเลิก</a>
        </div>
      </div>
    </form>
  </div>
</div>

<div id="editPromoModal" class="modal modal-fixed-footer">
  <form action="manage-promo.php" method="post" enctype="multipart/form-data">
    <div class="modal-content">
      <h5 class="modalTitleCenter">Edit promotion <span id="promoeditname"></span></h5>
      <div class="row modalImageEdit">
        <div class="col s12">
          <img class="responsive-img" id="promoeditoldfile" src="">
        </div>
      </div>
      <div class="row">
        <div class="input-field col s12 m12 l6">
          <h6 class="input-label">ชื่อโปรโมชั่น</h6>
          <input id="promoname" name="promoname" type="text" class="validate" required>
        </div>
        <input type="hidden" id="promoeditid" name="editid" value=""/>
        <div class="col s12 m12 l6 file-field filefield input-field">
          <div class="btn">
            <span>เลือกรูปภาพ</span>
            <input type="file" name="promoUpload" accept="image/*" id="promoUpload"/>
          </div>
          <div class="file-path-wrapper">
            <input class="file-path validate" type="text" class="validate">
          </div>
        </div>
        <div class="col s12 input-field">
          <h6 class='input-label'>ลิงค์</h6>
          <input id="url" name="url" type="text">
        </div>
      </div>
    </div>
    <div class="modal-footer row">
      <div class="col s6">
        <button class="waves-effect waves-light btn btn-block green" type="submit" name="editpro">บันทึก</button>
      </div>
      <div class="col s6">
        <a class="waves-effect waves-light btn btn-block modalClose grey">ยกเลิก</a>
      </div>
    </div>
  </form>
</div>

<script src="js/manage-promo.js"></script>
<?php require_once 'element/footer.php'; ?>
