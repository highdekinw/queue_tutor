<?php
	if(isset($_GET['img_url'])){
		$img_name = explode('/', $_GET['img_url']);
?>
<html>
  <head>
		<title>Payment Image - <?php echo $img_name[1]; ?></title>
		<link type="text/css" rel="stylesheet" href="css/materialize.min.css"  media="screen,projection"/>
		<script type="text/javascript" src="js/jquery.js"></script>
		<script type="text/javascript" src="js/materialize.min.js"></script>
		<link type="text/css" rel="stylesheet" href="css/font-awesome.min.css"/>
		<script type="text/JavaScript" src="js/printJS/jQuery.print.js" /></script>
  </head>
  <body class='grey darken-4'>
		<div class='' style=' position: fixed; right: 5; width: 25%'>
			<div class="card grey darken-4 right" style='opacity: 0.85;'>
						<div class='card-content '>
							<span class='white-text'><?php echo $img_name[1]; ?></span>
						</div>
            <div class="card-action">
						
              <a href='<?php echo $_GET['img_url'];?>' download="<?php echo $img_name[1]; ?>" class='white-text'><i class="fa fa-download" aria-hidden="true"></i> Download</a>
              <a class='white-text print-btn'  href='#'><i class="fa fa-print" aria-hidden="true"></i> Print</a>
						</div>
          </div>
		</div>
			<div class='row'>
					<div class='col s12'>
							<img 
								class='img' 
								style="max-width: 100%; max-height: 95vh; display: block; margin: auto; margin-top: 2vh;" 
								src='<?php echo $_GET['img_url'];?>'
							>
					</div>
			</div>
			<script>
				$('.print-btn').on('click', function(){
					$.print(".img" /*, options*/);
				});
			</script>
    </body>
</html>
<?php
    }
?>
