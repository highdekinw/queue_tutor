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
            echo "<td class='actionBtns'><a class='waves-effect waves-light btn green' href='backend/{$files['path']}'><i class='fa fa-download'></i></a> ";
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

  <h2>อัพโหลด</h2>
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
    </form>
</div>
<script>
var max_page;
var curr_page;

$(document).ready(function () {
  max_page = Math.ceil($("tr[data-number]").length / 20.0);
  curr_page = 1;
  filter_pagination();
});

$('#search_files').on("keyup paste",function(){
  var searchReg = new RegExp($(this).val(), 'igm');
  i = 1;
  $("tbody").children("tr").each(function(){
    var thisValid = false;
    thisValid = $(this).attr("data-file").match(searchReg) != null;
    if(thisValid) {
      // $(this).show();
      $(this).attr('data-number', i);
      $(this).attr('show', 'true');
      i++;
    }else {
      // $(this).hide();
      $(this).attr('data-number', -1);
      $(this).attr('show', 'false');
    }
  });
  max_page = Math.ceil($("tr[data-number][show=true]").length / 20.0)
  curr_page = 1;
  filter_pagination();
});  

$("#back_btn").on('click', function () {
  curr_page -= 1;
  filter_pagination();
});

$("#next_btn").on('click', function () {
  curr_page += 1;
  filter_pagination();
});

function filter_pagination() {
  show_limit = curr_page * 20;
  if (curr_page == 1) {
    $("#back_btn").hide();
  } else {
    $("#back_btn").show();
  }

  if (curr_page >= max_page) {
    $("#next_btn").hide();
  } else {
    $("#next_btn").show();
  }

  $("tr[data-number]").each(function () {
    var this_number = parseInt($(this).attr('data-number'));
    if (this_number <= show_limit && this_number > show_limit - 20) {
      $(this).show();
    } else {
      $(this).hide();
    }
  });
}
</script>
<?php require_once 'element/footer.php'; ?>
