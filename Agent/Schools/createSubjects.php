<?php
// Path : api/ClassRooms/addClassRoom
include("../connection.php");

if ($_SERVER['REQUEST_METHOD'] == 'POST'){
	$headers = getallheaders();
	if (array_key_exists('Authorization', $headers) && preg_match('/Bearer\s(\S+)/', $headers['Authorization'], $matches)){

		if(verifyToken($matches[1])){
			$schoolID = $_POST['SchoolID'];
			$checkDuplicateSujects = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as sc FROM `subjects` WHERE SchoolID = '$schoolID'"));
			if($checkDuplicateSujects['sc'] == 0){
				$classRoom = mysqli_query($conn, "SELECT * FROM `classrooms` WHERE SchoolID = '$schoolID'");
				if(mysqli_num_rows($classRoom)>0){
					while($row = mysqli_fetch_assoc($classRoom)){
						$ClassIndex = $row['ClassIndex'];
						$classRoomID = $row['ClassRoomID'];
						$findClassGroup = mysqli_fetch_assoc(mysqli_query($conn, "SELECT ClassRoomGroupID as gid FROM `defaultclassrooms` WHERE ClassIndex = '$ClassIndex'"));
						$ClassRoomGroupID = $findClassGroup['gid'];

						$subjectQuery = mysqli_query($conn, "SELECT * FROM `defaultsubjects` WHERE ClassRoomGroupID = '$ClassRoomGroupID'");
						while($subrow = mysqli_fetch_assoc($subjectQuery)){
							$SubjectID = $subrow['SubjectID'];
							$SubjectName = $subrow['SubjectName'];
							$SubjectTypeID = $subrow['SubjectTypeID'];
							mysqli_query($conn, "INSERT INTO `subjects`(`SchoolID`, `ClassRoomID`, `SubjectName`, `SubjectTypeID`, `SubjectTeacher`, `SubjectIndex`) VALUES ('$schoolID','$classRoomID','$SubjectName','$SubjectTypeID',NULL,'$SubjectID')");
						}
					}
				}
				http_response_code(200);
				header('Content-Type: application/json');
				$data = array ("Status"=> "OK", "Message" => "Subject Added Successfully.");
				echo json_encode( $data );
			}else{
				http_response_code(200);
				header('Content-Type: application/json');
				$data = array ("Status"=> "ERROR", "Message" => "Subjects Already Added");
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