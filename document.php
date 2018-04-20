<?php
  require_once 'element/header.php';
?>
<div class="container">
  <h2>ดาวน์โหลด</h2>
  <div class="row" id="fileList">
    <table>
      <thead>
        <tr><th>ชื่อไฟล์</th><th></th></tr>
      </thead>
      <tbody>
        <?php
          $sql = "SELECT * from files";
          $res = $mysqli->query($sql);
          while($files = $res->fetch_assoc()) {
            echo "<tr>";
            echo "<td>".$files['filename']."</td>";
            echo "<td class='actionBtns'><a class='waves-effect waves-light btn green' href='backend/{$files['path']}'><i class='fa fa-download'></i></a></td>";
            echo "</tr>";
          }
         ?>
      </tbody>
    </table>
  </div>
</div>
<?php require_once 'element/footer.php'; ?>
