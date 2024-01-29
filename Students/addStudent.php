<?php
// Path : api/ClassRooms/addClassRoom
include("../connection.php");

if ($_SERVER['REQUEST_METHOD'] == 'POST'){
	$headers = getallheaders();
	if (array_key_exists('Authorization', $headers) && preg_match('/Bearer\s(\S+)/', $headers['Authorization'], $matches)){

		if(verifyToken($matches[1])){
            $sid = getSchoolID($matches[1]);
			$SectionID = $_POST['studentclass'];
			$adno = $_POST['adno'];
			$studentname = ucfirst($_POST['studentname']);
			$fathername = ucfirst($_POST['fathername']);
			$mothername = ucfirst($_POST['mothername']);
			$sd = explode("/",$_POST['dob']);
			$dob = $sd[2]."-".$sd[0]."-".$sd[1];
			$rollno = $_POST['rollno'];
			$gender = $_POST['gender'];
			$category = $_POST['category'];
			$address = $_POST['address'];
			$mobile = $_POST['mobile'];
			$aadhar = $_POST['aadhar'];

			$getClassRoom = mysqli_fetch_assoc(mysqli_query($conn, "SELECT ClassRoomID FROM `classrooms_sections` WHERE SectionID = '$SectionID' LIMIT 1"));
			$classRoomID = $getClassRoom['ClassRoomID'];
			
			$addExamGroup = mysqli_query($conn, "INSERT INTO `students`(`Year`, `SchoolID`, `StudentName`, `StudentFatherName`, `StudentMotherName`, `DateofBirth`, `Category`, `StudentAddress`, `ClassRoomID`, `SectionID`, `AdmissionNo`, `Gender`, `RollNo`, `StudentMobileNo`, `StudentAadhar`, `StudentPhoto`)
			 VALUES ('2023','$sid','$studentname','$fathername','$mothername','$dob','$category','$address','$classRoomID','$SectionID','$adno','$gender','$rollno','$mobile','$aadhar','')");
			
			http_response_code(200);
			header('Content-Type: application/json');
			if($addExamGroup == TRUE){
				$data = array ("Status"=> "OK","Message" => "Student Added Successfully.");
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