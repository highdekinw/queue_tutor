<?php 
	require_once 'element/header.php'; 
	requireUserType(1);
?>
<div class="container">
	<h2>แดชบอร์ด</h2>
	<div class="row">
		<div class="col s12 m6">
			<div class="card ">
				<div class="card-content ">
					<span class="card-title">จำนวนการลงเรียนในแต่ละเทอม</span>
					<!-- <div class="row"> -->
					<!-- <div class="col s6" id="income-box" style="height: 15em;"></div> -->
					<div class="" id="seat-count-box" style="height: 15em;"></div>
					<!-- </div> -->
				</div>
			</div>
		</div>
		
		<div class="col s12 m6">
			<div class="card ">
				<div class="card-content ">
					<span class="card-title">ลำดับคอร์สที่มีการลงทะเบียนสูงสุด</span>
					<div id="most-reserve-course" style="height: 15em;"></div>
				</div>
			</div>
		</div>
		<div class="col s12">
			<div class="card ">
				<div class="card-content ">
					<span class="card-title"></span>
					<select id="term-select">
						<?php
							$sql = "SELECT * FROM term";
							$res = $mysqli->query($sql);
							while($r = $res->fetch_assoc()){
								echo "<option data-term='{$r['term']}' data-year='{$r['year']}'>{$r['term']}/{$r['year']}</option>";
							}
						?>
					</select>
					<div class="row">
						<div class="col s12" id="most-reserve" style="height: 17em"></div>
						<!-- <div class="col s6" id="most-revenue" style="height: 17em"></div> -->
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<script src="js/canvasjs.min.js"></script>
<script src="js/tether.min.js"></script>
<script src="js/manage-dashboard.js"></script>

<?php require_once 'element/footer.php'; ?>

<div class="col s12 m6 hidden">
	<div class="card ">
		<div class="card-content ">
			<span class="card-title">ลำดับคอร์สที่มีการทำรายได้สูงสุด</span>
			<div id="most-revenue-course" style="height: 15em;"></div>
		</div>
	</div>
</div>