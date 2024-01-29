<?php
// Path : api/Subjects/addSubject
include("../connection.php");

if ($_SERVER['REQUEST_METHOD'] == 'POST'){
	$headers = getallheaders();
	if (array_key_exists('Authorization', $headers) && preg_match('/Bearer\s(\S+)/', $headers['Authorization'], $matches)){

		if(verifyToken($matches[1])){
            $sid = getSchoolID($matches[1]);
			$subjectName = $_POST['subjectName'];
			$subjectTeacherName = $_POST['subjectTeacherName'];
			$subjectType = $_POST['subjectType'];
			$classID = $_POST['classID'];
			if($subjectTeacherName == "" || $subjectTeacherName == null){
				$addSubject = mysqli_query($conn, "INSERT INTO `subjects`(`Year`, `SchoolID`, `ClassRoomID`, `SubjectName`, `SubjectTypeID`, `SubjectTeacher`) VALUES ('2023','$sid','$classID','$subjectName','$subjectType',NULL)");
			}else{
				$addSubject = mysqli_query($conn, "INSERT INTO `subjects`(`Year`, `SchoolID`, `ClassRoomID`, `SubjectName`, `SubjectTypeID`, `SubjectTeacher`) VALUES ('2023','$sid','$classID','$subjectName','$subjectType','$subjectTeacherName')");
			}
			http_response_code(200);
			header('Content-Type: application/json');
			if($addSubject == TRUE){
				$data = array ("Status"=> "OK","Message" => "Subject Added Successfully.");
				echo json_encode( $data );
			}else{
				$data = array ("Status"=> "ERROR","Message" => "Failed");
				echo json_encode( $data );
			}

		}else{
			http_response_code(401);
			header('Content-Type: application/json');
			$data = array ("Message" => "Unauthorized");
			echo json_encode( $data );
		}

	}else{
		http_response_code(401);
		header('Content-Type: application/json');
		$data = array ("Message" => "Unauthorized");
		echo json_encode( $data );
	}
}

?>