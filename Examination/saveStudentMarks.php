<?php
// Path : api/Subjects/addSubject
include("../connection.php");

if ($_SERVER['REQUEST_METHOD'] == 'POST'){
	$headers = getallheaders();
	if (array_key_exists('Authorization', $headers) && preg_match('/Bearer\s(\S+)/', $headers['Authorization'], $matches)){

		if(verifyToken($matches[1])){
            $sid = getSchoolID($matches[1]);
			$marksArr = json_decode($_POST['MarksArr'],true);

			
			if (!empty($marksArr)) {
				foreach ($marksArr as $value) {
					$StudentId = $value['StudentId'];
					$SubjectId = $value['SubjectId'];
					$PaperId = $value['PaperId'];
					$Marks = $value['Marks'];
					$ClassRoomID = $value['ClassRoomID'];
					$validate = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as RowC FROM `student_paper_marks` WHERE PaperID = '$PaperId' AND StudentID = '$StudentId'"));
					if($validate['RowC']==0){
						if($Marks == ""){
							$saveMarks = mysqli_query($conn, "INSERT INTO `student_paper_marks`(`PaperID`, `StudentID`, `MarksObtained`, `SubjectID`) VALUES ('$PaperId','$StudentId',NULL,'$SubjectId')");
						}else{
							$saveMarks = mysqli_query($conn, "INSERT INTO `student_paper_marks`(`PaperID`, `StudentID`, `MarksObtained`, `SubjectID`) VALUES ('$PaperId','$StudentId','$Marks','$SubjectId')");
						}
					}else{
						if($Marks == ""){
							$updateMarks = mysqli_query($conn, "UPDATE `student_paper_marks` SET `MarksObtained` = NULL WHERE `PaperID` = '$PaperId' AND `StudentID` = '$StudentId'");
						}else{
							$updateMarks = mysqli_query($conn, "UPDATE `student_paper_marks` SET `MarksObtained` = '$Marks' WHERE `PaperID` = '$PaperId' AND `StudentID` = '$StudentId'");
						}
					}
					
				}
					http_response_code(200);
					header('Content-Type: application/json');
					$data = array ("Status"=> "OK","Message" => "Data Saved Successfully.");
					echo json_encode( $data );
			} else {
				http_response_code(400);
				header('Content-Type: application/json');
				$data = array ("Message" => "Invalid Data.");
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