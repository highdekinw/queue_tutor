<?php
  require_once 'element/header.php';
  requireUserType(1);
?>
<div class="container">
  <h2>เอกสาร</h2>
  <div class="row" id="fileList">
    <div class="input-field col s12 m6">
      <i class="fa fa-search prefix"></i>
      <input id="search_files" placeholder="ค้นหาเอกสาร..." type="text" >
    </div>
    <div class="col s6 barBtn" id="add_file_btn">
      <a data-target='add_file_modal' class="waves-effect waves-light btn blue"><i class="fa fa-plus left"></i>เพิ่มเอกสาร</a>
    </div>
    <table>
      <thead>
        <tr><th>ชื่อเอกสาร</th><th></th></tr>
      </thead>
      <tbody>
        <?php
          $sql = "SELECT * from files";
          $res = $mysqli->query($sql);
          $i = 1;
          while($files = $res->fetch_assoc()) {
            echo "<tr data-file='{$files['filename']}' data-number='{$i}'>";
            echo "<td>".$files['filename']."</td>";
            // echo "<td class='actionBtns'><a class='waves-effect waves-light btn green' href='backend/{$files['path']}'><i class='fa fa-download'></i></a> ";
            echo "
            <td class='actionBtns'>
              <a 
                data-target='edit_file_modal'
                data-edit-fileid='{$files['id']}' 
                data-edit-filename='{$files['filename']}'
                data-edit-path='".basename($files['path'])."' 
                class='waves-effect waves-light btn green' 
              >แก้ไข</a> ";
            // echo "<a class='waves-effect waves-light btn green' href='backend/{$files['path']}'><i class='fa fa-download'></i></a> ";
            echo "<a class='waves-effect waves-light btn red' href='backend/files.php?delete={$files['id']}'><i class='fa fa-trash'></i></a></td>";
            echo "</tr>";
            $i = $i + 1;
          }
         ?>
      </tbody>
    </table>
    <br/>
    <div id='back_btn' class='skip_search hidden left btn btn-default blue'>< BACK</div>
    <div id='next_btn' class='skip_search hidden right btn btn-default blue'>NEXT ></div>
  </div>

  <!-- <h2>อัพโหลด</h2>
  <form action="backend/files.php" method="post" enctype="multipart/form-data">
    <div class="file-field input-field">
      <div class="btn">
        <span>เลือกไฟล์</span>
        <input type="file" name="fileUpload" id="fileUpload">
      </div>
      <div class="file-path-wrapper">
        <input class="file-path validate" type="text">
      </div>
    </div>
    <button type="submit" class="waves-effect waves-light btn btn-block blue" name="upload">อัพโหลด</button>
  </form> -->
</div>

<div id="add_file_modal" class="modal">
  <div class="modal-content">
    <form action="backend/files.php" method="post" enctype="multipart/form-data">
      <h5 class="modalTitleCenter">เพิ่มเอกสาร</h5>
      <div class="row">
        <div class="input-field col s12">
          <h6 class="input-label">ชื่อ</h6>
          <input id="filename" name="filename" type="text" class="validate" required>
        </div>
        <div class="col s12 file-field filefield input-field">
          <div class="btn">
            <span>เลือกไฟล์</span>
            <input type="file" name="fileUpload" id="fileUpload">
          </div>
          <div class="file-path-wrapper">
            <input class="file-path validate" type="text">
          </div>
        </div>
      </div>
      <div class="row">
        <div class="col s6">
          <button class="waves-effect waves-light btn btn-block blue" type="submit" name="upload">เพิ่ม</button>
        </div>
        <div class="col s6">
          <a class="waves-effect waves-light btn btn-block modalClose grey">ยกเลิก</a>
        </div>
      </div>
    </form>
  </div>
</div>

<div id="edit_file_modal" class="modal">
  <div class="modal-content">
    <form action="backend/files.php" method="post" enctype="multipart/form-data">
      <h5 class="modalTitleCenter">แก้ไข</h5>
      <div class="row">
        <div class="input-field col s12">
          <h6 class="input-label">ชื่อ</h6>
          <input id="edit_filename" name="filename" type="text" class="validate" required>
        </div>
        <input type="hidden" id="edit_fileid" name="editid" value=""/>
        <div class="col s12 file-field filefield input-field">
          <div class="btn">
            <span>เลือกไฟล์</span>
            <input type="file" name="fileUpload" id="fileUpload">
          </div>
          <div class="file-path-wrapper">
            <input id="edit_path" class="file-path validate" type="text">
          </div>
        </div>
      </div>
      <div class="row">
        <div class="col s6">
          <button class="waves-effect waves-light btn btn-block blue" type="submit" name="update">แก้ไข</button>
        </div>
        <div class="col s6">
          <a class="waves-effect waves-light btn btn-block modalClose grey">ยกเลิก</a>
        </div>
      </div>
    </form>
  </div>
</div>

<script src="js/manage-download.js"></script>
<?php require_once 'element/footer.php'; ?>
