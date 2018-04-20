<?php
  require_once 'element/header.php';
  requireUserType(1);
  
  if (isset($_POST['addnewnews'])) {
    $sql = "insert into news (title, content) values ('{$_POST['newstitle']}', '{$_POST['newscontent']}')";
    $res = $mysqli->query($sql);
  }

  if (isset($_GET['delete_news'])) {
    $sql = "delete from news where id={$_GET['delete_news']}";
    $res = $mysqli->query($sql);
  }

  if (isset($_POST['editnews'])) {
    $sql = "UPDATE news set title='{$_POST['newstitle']}', content='{$_POST['newscontent']}' where id={$_POST['newsid']}";
    $res = $mysqli->query($sql);
    header('Location: manage-news.php?success=4');
  }
?>
<div class="container">
  <h4>ข่าวสาร</h4>
  <div class="row" id="newsbar">
    <div class="input-field col s6">
      <i class="fa fa-search prefix"></i>
      <input id="search_news" placeholder="ค้นหาข่าวสาร..." type="text" class="validate">
    </div>
    <div class="col s6 barBtn" id="addNewsBtn">
      <a data-target='addNewsModal' class="waves-effect waves-light btn blue"><i class="fa fa-plus left"></i>เพิ่มข่าวสาร</a>
    </div>
  </div>
  <div class="row">
    <div class="col s12">
      <table class="highlight">
        <thead>
          <tr><th colspan="2">หัวข้อ - เนื้อหา</th><th></th><th></th></tr>
        </thead>
        <tbody>
          <?php
            $sql_qnews = "select * from news";
            $news_res = $mysqli->query($sql_qnews);
            $i = 1;
            while($news = $news_res->fetch_assoc()) {
              echo "<tr data-newsid='{$news['id']}' data-number='{$i}'>";
              echo "<td class='newscontent' colspan='2'><span class='news-txt-title'><b>{$news['title']}</b></span> - <span class='news-txt-content'>{$news['content']}</span></td>
              <td class='actionBtns'>
              <a class='waves-effect waves-light btn editNews green' data-editnewsid='{$news['id']}'>แก้ไข</a>
              </td>
              <td class='actionBtns'>
              <a class='waves-effect waves-light btn red' href='manage-news.php?delete_news={$news['id']}'><i class='fa fa-trash'></i></a>
              </td>";
              echo "</tr>";
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

<div id="addNewsModal" class="modal">
  <div class="modal-content">
    <form action="manage-news.php" method="post">
      <h5 class="modalTitleCenter">เพิ่มข่าวสาร</h5>
      <div class="row">
        <div class="input-field col s12">
          <h6 class="input-label">หัวข้อ</h6>
          <input name="newstitle" type="text" class="validate" required>
        </div>
        <div class="input-field col s12">
          <h6 class="input-label">เนื้อหา</h6>
          <!--input id="newscontent" name="newscontent" type="text" class="validate" required-->
          <textarea name="newscontent" class="materialize-textarea" required></textarea>
        </div>
      </div>
      <div class="row">
        <div class="col s6">
          <button class="waves-effect waves-light btn btn-block blue" type="submit" name="addnewnews">เพิ่ม</button>
        </div>
        <div class="col s6">
          <a class="waves-effect waves-light btn btn-block modalClose grey">ยกเลิก</a>
        </div>
      </div>
    </form>
  </div>
</div>

<div id="editNewsModal" class="modal">
  <div class="modal-content">
    <form action="manage-news.php" method="post">
      <h5 class="modalTitleCenter">แก้ไขข่าวสาร</h5>
      <div class="row">
        <div class="input-field col s12">
          <h6 class="input-label">หัวข้อ</h6>
          <input id="newsid" type="hidden" name="newsid" value="">
          <input id="newstitle" name="newstitle" type="text" class="validate" required>
        </div>
        <div class="input-field col s12">
          <h6 class="input-label">เนื้อหา</h6>
          <!--input id="newscontent" name="newscontent" type="text" class="validate" required-->
          <textarea id="newscontent" name="newscontent" class="materialize-textarea" required></textarea>
        </div>
      </div>
      <div class="row">
        <div class="col s6">
          <button class="waves-effect waves-light btn btn-block green" type="submit" name="editnews">บันทึก</button>
        </div>
        <div class="col s6">
          <a class="waves-effect waves-light btn btn-block modalClose grey">ยกเลิก</a>
        </div>
      </div>
    </form>
  </div>
</div>

<script src="js/manage-news.js"></script>
<?php require_once 'element/footer.php'; ?>
