<?php
// Path : api/ClassRooms/addClassRoom
include("../connection.php");

if ($_SERVER['REQUEST_METHOD'] == 'POST'){
	$headers = getallheaders();
	if (array_key_exists('Authorization', $headers) && preg_match('/Bearer\s(\S+)/', $headers['Authorization'], $matches)){

		if(verifyToken($matches[1])){
            $sid = getSchoolID($matches[1]);
			$ClassRoomID = $_POST['ClassRoomID'];
			$SectionText = $_POST['SectionText'];
			$ClassTeacher = $_POST['ClassTeacherID'];

			if($ClassTeacher == "" || $ClassTeacher == null){
				mysqli_query($conn, "INSERT INTO `classrooms_sections`(`ClassRoomID`, `SectionText`, `ClassTeacher`) VALUES ('$ClassRoomID','$SectionText',NULL)");
			}else{
				mysqli_query($conn, "INSERT INTO `classrooms_sections`(`ClassRoomID`, `SectionText`, `ClassTeacher`) VALUES ('$ClassRoomID','$SectionText','$ClassTeacher')");
			}
			http_response_code(200);
			header('Content-Type: application/json');
			$data = array ("Status"=> "OK","Message" => "Section Added Successfully.");
			echo json_encode( $data );
			

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