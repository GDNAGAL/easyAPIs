<?php
// Path : api/ClassRooms/addClassRoom
include("../connection.php");

if ($_SERVER['REQUEST_METHOD'] == 'POST'){
	$headers = getallheaders();
	if (array_key_exists('Authorization', $headers) && preg_match('/Bearer\s(\S+)/', $headers['Authorization'], $matches)){

		if(verifyToken($matches[1])){
            $sid = getSchoolID($matches[1]);
			$teacherName = $_POST['teacherName'];
			$teacherDesignation = $_POST['teacherDesignation'];
			$teacherMobile = $_POST['teacherMobile'];
			
			$addTeacher = mysqli_query($conn, "INSERT INTO `teachers`(`SchoolID`, `TeacherName`, `Designation`, `TeacherMobile`) VALUES ('$sid','$teacherName','$teacherDesignation','$teacherMobile')");
			
			http_response_code(200);
			header('Content-Type: application/json');
			if($addTeacher == TRUE){
				$data = array ("Status"=> "OK","Message" => "Teacher Added Successfully.");
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