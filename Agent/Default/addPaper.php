<?php
// Path : api/Subjects/addSubject
include("../connection.php");

if ($_SERVER['REQUEST_METHOD'] == 'POST'){
	$headers = getallheaders();
	if (array_key_exists('Authorization', $headers) && preg_match('/Bearer\s(\S+)/', $headers['Authorization'], $matches)){

		if(verifyToken($matches[1])){
			$ExamID = $_POST['ExamID'];
			$SubjectID = $_POST['subjectIDName'];
			$SubjectID = $_POST['subjectIDName'];
			$PaperDisplayText = $_POST['paperName'];
			$PaperDisplayTextHindi = $_POST['paperNameHindi'];
			$PaperMM = $_POST['MaxMarksName'];
			$ClassRoomGroupID = $_POST['ClassRoomGroupID'];

			
			$addPaper = mysqli_query($conn, "INSERT INTO `defaultpaper`(`ExamIndex`, `ClassRoomIndex`, `SubjectIndex`, `PaperDisplayText`, `PaperDisplayTextHindi`, `PaperMM`) VALUES ('$ExamID','$ClassRoomGroupID','$SubjectID','$PaperDisplayText','$PaperDisplayTextHindi','$PaperMM')");
			
			http_response_code(200);
			header('Content-Type: application/json');
			if($addPaper == TRUE){
				$data = array ("Status"=> "OK","Message" => "Paper Added Successfully.");
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