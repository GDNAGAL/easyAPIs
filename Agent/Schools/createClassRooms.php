<?php
// Path : api/ClassRooms/addClassRoom
include("../connection.php");

if ($_SERVER['REQUEST_METHOD'] == 'POST'){
	$headers = getallheaders();
	if (array_key_exists('Authorization', $headers) && preg_match('/Bearer\s(\S+)/', $headers['Authorization'], $matches)){

		if(verifyToken($matches[1])){
			$schoolID = $_POST['SchoolID'];
			$checkDuplicateClassRooms = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as sc FROM `classrooms` WHERE SchoolID = '$schoolID'"));
			if($checkDuplicateClassRooms['sc'] == 0){
				$selectDefaultClass = mysqli_query($conn, "SELECT * FROM `defaultclassrooms`");
				while($DfcRow = mysqli_fetch_assoc($selectDefaultClass)){
					$ClassRoomName = $DfcRow['ClassRoomName'];
					$classRoomIndex = $DfcRow['ClassIndex'];
					$addClassRoom = mysqli_query($conn, "INSERT INTO `classrooms`(`SchoolID`, `ClassRoomName`, `ClassIndex`) VALUES ('$schoolID','$ClassRoomName','$classRoomIndex')");
					$ClassRoomID = mysqli_insert_id($conn);
					$addClassRoom_Section = mysqli_query($conn, "INSERT INTO `classrooms_sections`(`ClassRoomID`, `SectionText`, `ClassTeacher`)  VALUES ('$ClassRoomID','A',NULL)");
				}
				http_response_code(200);
				header('Content-Type: application/json');
				$data = array ("Status"=> "OK", "Message" => "ClassRoom Added Successfully.");
				echo json_encode( $data );
			}else{
				http_response_code(200);
				header('Content-Type: application/json');
				$data = array ("Status"=> "ERROR", "Message" => "ClassRoom Already Added");
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