<?php
// Path : api/ClassRooms/addClassRoom
include("../connection.php");

if ($_SERVER['REQUEST_METHOD'] == 'POST'){
	$headers = getallheaders();
	if (array_key_exists('Authorization', $headers) && preg_match('/Bearer\s(\S+)/', $headers['Authorization'], $matches)){

		if(verifyToken($matches[1])){
			$schoolID = $_POST['SchoolID'];
			$activateSchool = mysqli_query($conn, "UPDATE schools set SchoolStatus = '1' WHERE SchoolID = '$schoolID'");
			
			http_response_code(200);
			header('Content-Type: application/json');
			$data = array ("Status"=> "OK", "Message" => "School Activated Successfully.");
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