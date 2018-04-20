<?php
	require_once 'setting.php';
	if($_POST['type'] == 1){
		$sql = "SELECT course._year as year, course._term as term, COUNT(seatOfCourse.id) as seatCount,SUM(course.price) as income, SUM(seatOfCourse.existing) as existing FROM course,seatOfCourse WHERE course.id=seatOfCourse._course AND seatOfCourse._student IS NOT NULL GROUP BY course._year,course._term";
		$res = $mysqli->query($sql);
		$result = [];
		while($r = $res->fetch_assoc()){
			$result[] = $r;
		}
		echo json_encode($result);
	}
	else if($_POST['type'] == 2){
		$sql = "SELECT course.name, seatOfCourse._course, SUM(course.price) as income, SUM(seatOfCourse.existing) as existing, COUNT(seatOfCourse.id) as seatCount, course._term as term, course._year as year FROM course,seatOfCourse WHERE seatOfCourse._course=course.id AND seatOfCourse._student IS NOT NULL GROUP BY seatOfCourse._course ORDER BY course._year, course._term";
		$res = $mysqli->query($sql);
		$result = [];
		while($r = $res->fetch_assoc()){
			$result[] = $r;
		}
		echo json_encode($result);
	}
?>
