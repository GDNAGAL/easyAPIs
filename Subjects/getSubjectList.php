<?php
// Path : api/Subjects/getSubjectList.php
include("../connection.php");

if ($_SERVER['REQUEST_METHOD'] == 'POST'){
	$headers = getallheaders();
	if (array_key_exists('Authorization', $headers) && preg_match('/Bearer\s(\S+)/', $headers['Authorization'], $matches)){

		if(verifyToken($matches[1])){
            $sid = getSchoolID($matches[1]);
			$classID = $_POST['classID'];
			// $class = mysqli_query($conn, "SELECT * FROM `classrooms` JOIN teachers ON classrooms.ClassTeacher = teachers.TeacherID WHERE classrooms.SchoolID = '$sid' ORDER by `ClassRoomID`");
			$class = mysqli_query($conn, "SELECT SubjectID,SubjectName,SubjectType,TeacherID,TeacherName FROM `subjects`
			LEFT JOIN `teachers` ON `subjects`.`SubjectTeacher` = `teachers`.`TeacherID`
			LEFT JOIN subject_types ON `subjects`.`SubjectTypeID` = `subject_types`.`SubjectTypeID`
			WHERE subjects.SchoolID = '$sid' AND subjects.SubjectTeacher IS NULL AND ClassRoomID = $classID OR subjects.SchoolID = '$sid' AND teachers.TeacherID IS NOT NULL AND ClassRoomID = $classID order by subjects.SubjectTypeID");
			http_response_code(200);
			header('Content-Type: application/json');
			if(mysqli_num_rows($class)>0){
				while($row = mysqli_fetch_assoc($class)) {
					$records[] = $row;
					}
				$class = mysqli_fetch_assoc(mysqli_query($conn, "SELECT ClassRoomID, ClassRoomName FROM `classrooms` WHERE ClassRoomID = $classID"));
				$data = array ("Status"=> "OK","Message" => "Success", "SubjectList" => $records, "ClassDetail" => $class);
				echo json_encode( $data );
			}else{
				$class = mysqli_fetch_assoc(mysqli_query($conn, "SELECT ClassRoomID, ClassRoomName FROM `classrooms` WHERE ClassRoomID = $classID"));
				$data = array ("Status"=> "NOT_FOUND","Message" => "No Subject Found", "ClassDetail" => $class);
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