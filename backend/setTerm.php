<?php
	require_once 'setting.php';
	if(isset($_POST['setterm'])){
		$year = $_POST['year'];
		$term = $_POST['term'];
		$res = $mysqli->query("UPDATE term SET current=0");
		$sql = "UPDATE term SET current=1 WHERE year='{$year}' AND term='{$term}'";
		$res = $mysqli->query($sql);
		header('Location: ../manage-course.php?success=4'); 
	}else{
		header('Location: ../manage-course.php'); 
	}
?>