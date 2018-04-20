<?php
	if(isset($_POST['courseid'])){
		require_once "setting.php";
		$sql = "SELECT * FROM course WHERE id={$_POST['courseid']}";
		$results = $mysqli->query($sql);
		$result = $results->fetch_assoc();
		echo json_encode($result);
	}
?>