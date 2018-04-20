<?php
	require_once 'setting.php';
	if(isset($_POST['subjectid'])){
		$sql = "SELECT COUNT(seatOfCourse.id) as count,
                    course.id as courseid
						FROM course
						INNER JOIN term
						ON course._term=term.term
						AND course._year=term.year
						AND term.current=1
						LEFT JOIN seatOfCourse
						ON seatOfCourse._course=course.id
						AND seatOfCourse._student IS NOT NULL
						WHERE course.subject={$_POST['subjectid']}
						GROUP BY seatOfCourse._course";
		// $sql = "SELECT  COUNT(seatOfCourse.id) as count,
    //                 course.id as courseid
    //         FROM seatOfCourse, course, term
    //         WHERE seatOfCourse._course=course.id
    //         AND course.subject={$_POST['subjectid']}
    //         AND seatOfCourse._student IS NOT NULL
    //         AND course._term=term.term
    //         AND course._year=term.year
    //         AND term.current=1
    //         GROUP BY seatOfCourse._course";
		$res = $mysqli->query($sql);
		$result = [];
		while($r = $res->fetch_assoc()){
			$result[] = $r;
		}
		echo json_encode($result);
	}
?>
