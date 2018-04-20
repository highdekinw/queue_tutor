<?php
	require_once 'setting.php';
	if(isset($_POST['userid'])){
		$userid = $_POST['userid'];
		$sql = "SELECT *,course.id as courseid, course.name as courseName, seat.name as seatName, room.name as roomName FROM course,seatOfCourse,seat,room WHERE seatOfCourse._student='{$userid}' AND course.id=seatOfCourse._course AND seat.id=seatOfCourse._seat AND room.id=course._room";
		$res = $mysqli->query($sql);
		$course = array();
		while($r = $res->fetch_assoc()){
			array_push($course, $r);
		}
		header('Content-type:application/json;charset=utf-8');
		echo json_encode($course);
	}
?>