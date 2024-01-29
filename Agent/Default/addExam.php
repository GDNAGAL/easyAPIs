<?php
// Path : api/Subjects/addSubject
include("../connection.php");

if ($_SERVER['REQUEST_METHOD'] == 'POST'){
	$headers = getallheaders();
	if (array_key_exists('Authorization', $headers) && preg_match('/Bearer\s(\S+)/', $headers['Authorization'], $matches)){

		if(verifyToken($matches[1])){
			$ExamText = $_POST['examName'];
			$ExamTextHindi = $_POST['examNameHindi'];

			
			$addExamGroup = mysqli_query($conn, "INSERT INTO `defaultexams`(`ExamText`, `ExamTextHindi`) VALUES ('$ExamText','$ExamTextHindi')");
			
			http_response_code(200);
			header('Content-Type: application/json');
			if($addExamGroup == TRUE){
				$data = array ("Status"=> "OK","Message" => "Exam Added Successfully.");
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