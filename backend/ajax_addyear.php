<?php
	require_once 'setting.php';
	if(isset($_POST)){
		$year = $_POST['year'];
		$sql = "INSERT INTO term(year,term) VALUES ('{$year}',1)";
		$res = $mysqli->query($sql);
		$sql = "INSERT INTO term(year,term) VALUES ('{$year}',2)";
		$res = $mysqli->query($sql);
	}
?>