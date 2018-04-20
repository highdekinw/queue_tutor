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

  if (isset($_POST['addnewpro'])) {
    $target_dir = "upload/";
    $filename = date("YmdHisv").'-'.basename($_FILES["promoUpload"]["name"]);
    $target_file = $target_dir . $filename;
    $fileType = pathinfo($target_file,PATHINFO_EXTENSION);
    if (move_uploaded_file($_FILES["promoUpload"]["tmp_name"], $target_file)) {
        $mysqli->query("insert into promotion (img, name) values ('{$target_file}','{$_POST['promoname']}')");
        header("Location: announcement.php?success=2");
      } else {
        header("Location: announcement.php?error=2");
      }
  }

  if (isset($_GET['delete_pro'])) {
    $rmfilesql = "SELECT * from promotion where id={$_GET['delete_pro']}";
    $rmfileres = $mysqli->query($rmfilesql);
    $file = $rmfileres->fetch_assoc();
    unlink($file['img']);
    $sql = "DELETE from promotion where id={$_GET['delete_pro']}";
    $res = $mysqli->query($sql);
  }

  if (isset($_POST['addnewgal'])) {
    $target_dir = "upload/";
    $filename = date("YmdHisv").'-'.basename($_FILES["galUpload"]["name"]);
    $target_file = $target_dir . $filename;
    $fileType = pathinfo($target_file,PATHINFO_EXTENSION);
    if (move_uploaded_file($_FILES["galUpload"]["tmp_name"], $target_file)) {
        $mysqli->query("insert into gallery (img, name) values ('{$target_file}','{$_POST['galname']}')");
        header("Location: announcement.php?success=2");
      } else {
        echo "error !";
        header("Location: announcement.php?error=2");
      }
  }

  if (isset($_GET['delete_gal'])) {
    $rmfilesql = "SELECT * from gallery where id={$_GET['delete_gal']}";
    $rmfileres = $mysqli->query($sql);
    $file = $rmfileres->fetch_assoc();
    unlink($file['img']);
    $sql = "DELETE from gallery where id={$_GET['delete_gal']}";
    $res = $mysqli->query($sql);
  }
?>
<div class="container">
  <h4>Promotion</h4>
  <div class="row">
    <div class="col s12">
      <table>
        <thead>
          <tr>
            <td>Promotion</td><td>Action</td>
          </tr>
        </thead>
        <tbody>
          <?php
            $prosql = "select * from promotion";
            $prores = $mysqli->query($prosql);
            while ($promo = $prores->fetch_assoc()) {
              echo "<tr><td>{$promo['name']}</td><td><a href='announcement.php?delete_pro={$promo['id']}'>Delete</a></td></tr>";
            }
          ?>
        </tbody>
      </table>
    </div>
    <div class="col s12">
      <h5>Add Promotion</h5>
      <form class="col s12 action" action="announcement.php" method="post" enctype="multipart/form-data">
        <div class="row">
          <div class="input-field col s6">
            Select image to upload: <input type="file" name="promoUpload" id="promoUpload"/>
          </div>
          <div class="input-field col s6">
            <input id="promoname" placeholder="Promotion name" name="promoname" type="text" class="validate">
            <label for="promoname">Promotion Name</label>
          </div>
          <div class="col s12">
            <button type="submit" name="addnewpro">Add!</button>
          </div>
        </div>
      </form>
    </div>
  </div>
  <h4>News</h4>
  <div class="row">
    <div class="col s12">
      <table>
        <thead>
          <tr><td>Title</td><td>Content</td><td>Action</td></tr>
        </thead>
        <tbody>
          <?php
            $sql_qnews = "select * from news";
            $news_res = $mysqli->query($sql_qnews);
            while($news = $news_res->fetch_assoc()) {
              echo "<tr>";
              echo "<td>{$news['title']}</td><td>{$news['content']}</td><td><a href='announcement.php?delete_news={$news['id']}'>Delete</a></td>";
              echo "</tr>";
            }
          ?>
        </tbody>
      </table>
    </div>
    <div class="col s12">
      <h5>Add News</h5>
      <div class="row">
       <form class="col s12 action" action="announcement.php" method="post">
         <div class="row">
           <div class="input-field col s6">
             <input placeholder="หัวข้อข่าว" id="newstitle" name="newstitle" type="text" class="validate">
             <label for="newstitle">News Title</label>
           </div>
           <div class="input-field col s12">
             <input id="newscontent" placeholder="เนื้อหาข่าว" name="newscontent" type="text" class="validate">
             <label for="newscontent">News Content</label>
           </div>
           <div class="col s12">
             <button type="submit" name="addnewnews">Add!</button>
           </div>
         </div>
       </form>
     </div>
    </div>
  </div>
  <h4>Gallery</h4>
  <div class="row">
    <div class="col s12">
      <table>
        <thead>
          <tr>
            <td>Image</td><td>Action</td>
          </tr>
        </thead>
        <tbody>
          <?php
            $galsql = "select * from gallery";
            $galres = $mysqli->query($galsql);
            while ($galimg = $galres->fetch_assoc()) {
              echo "<tr><td>{$galimg['name']}</td><td><a href='announcement.php?delete_gal={$galimg['id']}'>Delete</a></td></tr>";
            }
          ?>
        </tbody>
      </table>
    </div>
    <div class="col s12">
      <h5>Add Image to Gallery</h5>
      <form class="col s12 action" action="announcement.php" method="post" enctype="multipart/form-data">
        <div class="row">
          <div class="input-field col s6">
            Select image to upload: <input type="file" name="galUpload" id="galUpload"/>
          </div>
          <div class="input-field col s6">
            <input id="galname" placeholder="Image name" name="galname" type="text" class="validate">
            <label for="galname">Image Name</label>
          </div>
          <div class="col s12">
            <button type="submit" name="addnewgal">Add!</button>
          </div>
        </div>
      </form>
    </div>
  </div>

</div>
<?php require_once 'element/footer.php'; ?>
