<?php
// Path : 
include("../connection.php");

if ($_SERVER['REQUEST_METHOD'] == 'POST'){
	$headers = getallheaders();
	if (array_key_exists('Authorization', $headers) && preg_match('/Bearer\s(\S+)/', $headers['Authorization'], $matches)){

		if(verifyToken($matches[1])){
            $sid = getSchoolID($matches[1]);


			$selectClass = mysqli_query($conn, "SELECT classrooms_sections.ClassRoomID, ClassRoomName, SectionText, SectionID FROM `classrooms_sections` JOIN `classrooms` ON classrooms_sections.ClassRoomID = classrooms.ClassRoomID WHERE SchoolID = '$sid' order by ClassRoomID");
			http_response_code(200);
			header('Content-Type: application/json');
			if(mysqli_num_rows($selectClass)>0){
			while($classRow = mysqli_fetch_assoc($selectClass)){
				$SectionID = $classRow['SectionID'];
				$ClassRoomID = $classRow['ClassRoomID'];

				$totalPaperq = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(PaperID) as totalPaper From examdesign WHERE ClassRoomID = '$ClassRoomID'"));
				$totalPaperMarksq = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(id) as totalPaperMarks From student_paper_marks WHERE StudentID IN(Select StudentID From students WHERE ClassRoomID = '$ClassRoomID' AND SectionID = '$SectionID') AND MarksObtained IS NOT NULL"));
				$totalStudentsq = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(StudentID) as totalStudents From students WHERE ClassRoomID = '$ClassRoomID' AND SectionID = '$SectionID'"));
				
				$totalPaper = $totalPaperq['totalPaper'];
				$totalPaperMarks = $totalPaperMarksq['totalPaperMarks'];
				$totalStudents = $totalStudentsq['totalStudents'];

				$s = $totalPaper * $totalStudents;
				$c_percent = 0;
				if($s != 0){
					$c_percent = ($totalPaperMarks * 100) / $s;
				}
				$classRow['CompletedPercent'] = round($c_percent,2);
				$classRow['Students'] = $totalStudents;
				$records[] = $classRow;
			}
				$data = array ("Status"=> "OK","Message" => "Success", "ClassRoom" => $records);
				echo json_encode( $data );
			}else{
				$data = array ("Status"=> "NOT_FOUND","Message" => "No Exams are Found");
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