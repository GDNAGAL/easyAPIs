<?php
// Path : api/ClassRooms/addClassRoom
include("../connection.php");

if ($_SERVER['REQUEST_METHOD'] == 'POST'){
	$headers = getallheaders();
	if (array_key_exists('Authorization', $headers) && preg_match('/Bearer\s(\S+)/', $headers['Authorization'], $matches)){

		if(verifyToken($matches[1])){
			$schoolID = $_POST['SchoolID'];
			$checkDuplicateExams = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as sc FROM `exams` WHERE SchoolID = '$schoolID'"));
			if($checkDuplicateExams['sc'] == 0){
				$addExam = mysqli_query($conn, "INSERT INTO `exams`(`ExamText`, `ExamTextHindi`, `ExamIndex`, `SchoolID`) SELECT ExamText, ExamTextHindi, ExamID, $schoolID FROM `defaultexams`");
				
				function getExamID($ExamIndex){
					global $conn, $schoolID;
					$eRow = mysqli_fetch_assoc(mysqli_query($conn, "SELECT ExamID FROM `exams` WHERE ExamIndex = '$ExamIndex' AND SchoolID = '$schoolID'"));
					return $eRow['ExamID'];
				}

				//select Class
				$classRoom = mysqli_query($conn, "SELECT * FROM `classrooms` WHERE SchoolID = '$schoolID'");
				while($row = mysqli_fetch_assoc($classRoom)){
					$classRoomID = $row['ClassRoomID'];
					// echo $classRoomID;
					$classRoomIndex = $row['ClassIndex'];
					$classGID = mysqli_fetch_assoc(mysqli_query($conn, "SELECT ClassRoomGroupID FROM `defaultclassrooms` WHERE ClassIndex = '$classRoomIndex'"));
					$ClassRoomGroupID = $classGID['ClassRoomGroupID'];
					//select Subjects
					$subjects = mysqli_query($conn, "SELECT * FROM `subjects` WHERE ClassRoomID = '$classRoomID'");
					if(mysqli_num_rows($subjects)>0){
						while($subjectrow = mysqli_fetch_assoc($subjects)){
							$subjectID = $subjectrow['SubjectID'];
							$subjectIndex = $subjectrow['SubjectIndex'];

							$papers = mysqli_query($conn, "SELECT * FROM `defaultpaper` WHERE ClassRoomIndex = '$ClassRoomGroupID' AND SubjectIndex = '$subjectIndex'");
							if(mysqli_num_rows($papers)>0){
								while($paperRow = mysqli_fetch_assoc($papers)){
									$ExamID = getExamID($paperRow['ExamIndex']);
									$p = $paperRow['PaperDisplayText'];
									$pH = $paperRow['PaperDisplayTextHindi'];
									$mm = $paperRow['PaperMM'];
									$addPaper = mysqli_query($conn, "INSERT INTO `examdesign`(`ClassRoomID`, `SubjectID`, `ExamID`, `PaperDisplayText`, `PaperDisplayTextHindi`, `PaperMM`) VALUES ('$classRoomID','$subjectID','$ExamID','$p','$pH','$mm')");
								}
							}
						}
					}
				}

				http_response_code(200);
				header('Content-Type: application/json');
				$data = array ("Status"=> "OK", "Message" => "Exam Added Successfully.");
				echo json_encode( $data );
			}else{
				http_response_code(200);
				header('Content-Type: application/json');
				$data = array ("Status"=> "ERROR", "Message" => "Exams Already Added");
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