<?php
// Path : api/ClassRooms/addClassRoom
include("../connection.php");

if ($_SERVER['REQUEST_METHOD'] == 'POST'){
	$headers = getallheaders();
	if (array_key_exists('Authorization', $headers) && preg_match('/Bearer\s(\S+)/', $headers['Authorization'], $matches)){

		if(verifyToken($matches[1])){
            $sid = getSchoolID($matches[1]);
			$ClassRoomID = $_POST['ClassRoomID'];
			$ClassRoomName = $_POST['ClassRoomName'];
			$SectionID = $_POST['SectionID'];
			$SectionText = $_POST['SectionText'];
			$ClassTeacher = $_POST['ClassTeacher'];

			$updateClass = mysqli_query($conn, "UPDATE `classrooms` SET `ClassRoomName`='$ClassRoomName' WHERE ClassRoomID = '$ClassRoomID' AND SchoolID = '$sid'");

			if($ClassTeacher == "" || $ClassTeacher == null){
				$updateSection = mysqli_query($conn, "UPDATE `classrooms_sections` SET `SectionText`='$SectionText', `ClassTeacher` = NULL WHERE SectionID = '$ClassRoomID' AND ClassRoomID = '$ClassRoomID'");
			}else{
				$updateSection = mysqli_query($conn, "UPDATE `classrooms_sections` SET `SectionText`='$SectionText', `ClassTeacher` = '$ClassTeacher' WHERE SectionID = '$SectionID' AND ClassRoomID = '$ClassRoomID'");
			}
			http_response_code(200);
			header('Content-Type: application/json');
			$data = array ("Status"=> "OK","Message" => "ClassRoom Updated Successfully.");
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