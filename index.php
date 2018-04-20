<?php require_once 'element/header.php'; ?>
<div class="container">
  <h4>โปรโมชั่น</h4>
  <div class="row">
    <div class="col s12">
    <div class="carousel carousel-slider" id="promo_firstpage">
      <?php
        $prosqlget = "select * from promotion";
        $prores = $mysqli->query($prosqlget);
        while ($proimg = $prores->fetch_assoc()) {
          echo "<a class='carousel-item' href='{$proimg['url']}'><img src='{$proimg['img']}'></a>";
        }
      ?>
    </div>
    </div>
  </div>
  <h4>ข่าวสาร</h4>
  <div class="row">
    <?php
      $sql = "select * from news";
      $res = $mysqli->query($sql);
      while($news = $res->fetch_assoc()) {
        echo "<div class='col s12 m4 newscard'>";
        echo "  <div class='card blue-grey darken-1'>";
        echo "    <div class='card-content white-text'>";
        echo "      <h6 class='card-title'>{$news['title']}</h6>";
        echo "      <p>{$news['content']}</p>";
        echo "    </div>";
        echo "  </div>";
        echo "</div>";
      }
    ?>
  </div>
  <h4>คลังภาพ</h4>
  <div class="row">
    <div class="col s12">
    <div class="carousel" id="gallery_firstpage">
      <?php
        $galsqlget = "select * from gallery";
        $galres = $mysqli->query($galsqlget);
        while ($galimg = $galres->fetch_assoc()) {
          echo "<a class='carousel-item viewable' data-src='{$galimg['img']}' href='#!'><img src='{$galimg['img']}'></a>";
        }
      ?>
    </div>
    </div>
  </div>
</div>
<div class="photoviewer">
  <img src="" class='z-depth-5'>
  <div class="foot-text">
    Click anywhere to close.
  </div>
</div>
<?php require_once 'element/footer.php'; ?>
