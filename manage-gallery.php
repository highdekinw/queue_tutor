<?php
  require_once 'element/header.php';
  requireUserType(1);

  if (isset($_POST['addnewgal'])) {
    $target_dir = "upload/";
    $filename = date("YmdHisv").'-'.basename($_FILES["galUpload"]["name"]);
    $target_file = $target_dir . $filename;
    $fileType = pathinfo($target_file,PATHINFO_EXTENSION);
    if (move_uploaded_file($_FILES["galUpload"]["tmp_name"], $target_file)) {
      $mysqli->query("insert into gallery (img, name) values ('{$target_file}','{$_POST['galname']}')");
      header("Location: manage-gallery.php?success=2");
    } else {
      header("Location: manage-gallery.php?error=2");

    }
  }

  if (isset($_GET['delete_gal'])) {
    $rmfilesql = "SELECT * from gallery where id={$_GET['delete_gal']}";
    $rmfileres = $mysqli->query($rmfilesql);
    $file = $rmfileres->fetch_assoc();
    unlink($file['img']);
    $sql = "DELETE from gallery where id={$_GET['delete_gal']}";
    $res = $mysqli->query($sql);
  }

  if (isset($_POST["editgal"])) {
    $sql = "UPDATE gallery set name='{$_POST['galname']}' where id={$_POST['editid']}";
    $res = $mysqli->query($sql);
    if ($_FILES['galUpload']['error'] == 0) { // if file changes
      $rmfilesql = "SELECT * from gallery where id={$_POST['editid']}";
      $rmfileres = $mysqli->query($rmfilesql);
      $file = $rmfileres->fetch_assoc();
      unlink($file['img']);

      $target_dir = "upload/";
      $filename = date("YmdHisv").'-'.basename($_FILES["galUpload"]["name"]);
      $target_file = $target_dir . $filename;
      $fileType = pathinfo($target_file,PATHINFO_EXTENSION);
      if (move_uploaded_file($_FILES["galUpload"]["tmp_name"], $target_file)) {
        $mysqli->query("UPDATE gallery set img='{$target_file}' where id={$_POST['editid']}");
      } else {
        header("Location: manage-gallery.php?error=2");
      }
    }
    header("Location: manage-gallery.php?success=4");
  }
?>
<div class="container">
  <h4>คลังภาพ</h4>
  <div class="row" id="galbar">
    <div class="input-field col s6">
      <i class="fa fa-search prefix"></i>
      <input id="search_gal" placeholder="ค้นหารูปภาพ..." type="text" class="validate">
    </div>
    <div class="col s6 barBtn" id="addGalBtn">
      <a data-target='addGalModal' class="waves-effect waves-light btn blue"><i class="fa fa-plus left"></i>เพิ่มรูปภาพ</a>
    </div>
  </div>
  <div class="row">
    <div class="col s12">
      <table class="highlight">
        <thead>
          <tr>
            <td>ชื่อ</td>
            <td></td>
            <td>รูปภาพ</td>
            <td></td>
          </tr>
        </thead>
        <tbody>
          <?php
            $galsql = "select * from gallery";
            $galres = $mysqli->query($galsql);
            $i = 1;
            while ($galitem = $galres->fetch_assoc()) {
              echo "<tr data-galname='{$galitem['name']}' data-number='{$i}'>
              <td colspan='2'>{$galitem['name']}</td>
              <td><img src='{$galitem['img']}' style='max-widht: 50px; max-height: 50px;'></img></td>
              <td class='actionBtns'><a class='waves-effect waves-light btn editGal green' data-editgalid='{$galitem['id']}' data-originalfile='{$galitem['img']}'>แก้ไข</a>
              <a class='waves-effect waves-light btn red' href='manage-gallery.php?delete_gal={$galitem['id']}'><i class='fa fa-trash'></i></a></td></tr>";
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

<div id="addGalModal" class="modal">
  <div class="modal-content">
    <form action="manage-gallery.php" method="post" enctype="multipart/form-data">
      <h5 class="modalTitleCenter">เพิ่มรูปภาพ</h5>
      <div class="row">
        <div class="input-field col s12 m12 l6">
          <h6 class="input-label">ชื่อ</h6>
          <input name="galname" type="text" class="validate" required>
        </div>
        <div class="col s12 m12 l6 file-field filefield input-field">
          <div class="btn">
            <span>เลือกรูปภาพ</span>
            <input type="file" name="galUpload" accept="image/*" />
          </div>
          <div class="file-path-wrapper">
            <input class="file-path validate" type="text">
          </div>
        </div>
      </div>
      <div class="row">
        <div class="col s6">
          <button class="waves-effect waves-light btn btn-block blue" type="submit" name="addnewgal">เพิ่ม</button>
        </div>
        <div class="col s6">
          <a class="waves-effect waves-light btn btn-block modalClose grey">ยกเลิก</a>
        </div>
      </div>
    </form>
  </div>
</div>

<div id="editGalModal" class="modal modal-fixed-footer">
  <form action="manage-gallery.php" method="post" enctype="multipart/form-data">
    <div class="modal-content">
      <h5 class="modalTitleCenter">แก้ไขรูปภาพ <span id="galeditname"></span></h5>
      <div class="row modalImageEdit">
        <div class="col s12">
          <img class="responsive-img" id="galeditoldfile" src="">
        </div>
      </div>
      <div class="row">
        <div class="input-field col s12 m12 l6">
          <h6 class="input-label">ชื่อ</h6>
          <input id="galname" name="galname" type="text" class="validate" required>
        </div>
        <input type="hidden" id="galeditid" name="editid" value=""/>
        <div class="col s12 m12 l6 file-field filefield input-field">
          <div class="btn">
            <span>เลือกรูปภาพ</span>
            <input type="file" name="galUpload" accept="image/*" id="galUpload"/>
          </div>
          <div class="file-path-wrapper">
            <input class="file-path validate" type="text">
          </div>
        </div>
      </div>
    </div>
    <div class="modal-footer row">
      <div class="col s6">
        <button class="waves-effect waves-light btn btn-block green" type="submit" name="editgal">บันทึก</button>
      </div>
      <div class="col s6">
        <a class="waves-effect waves-light btn btn-block modalClose grey">ยกเลิก</a>
      </div>
    </div>
  </form>
</div>

<script src="js/manage-gallery.js"></script>
<?php require_once 'element/footer.php'; ?>
