<?php
// Path : api/ClassRooms/addClassRoom
include("../connection.php");

if ($_SERVER['REQUEST_METHOD'] == 'POST'){
	$headers = getallheaders();
	if (array_key_exists('Authorization', $headers) && preg_match('/Bearer\s(\S+)/', $headers['Authorization'], $matches)){

		if(verifyToken($matches[1])){
            $agentid = getAgentID($matches[1]);
			$UserID = $_POST['UserID'];
			$SchoolID = $_POST['SchoolID'];

			$checkDuplicateSchool = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(SchoolUserName) as sn FROM `schools` WHERE SchoolUserName = '$UserID' AND SchoolID <> '$SchoolID'"));
			if($checkDuplicateSchool['sn'] == 0){

				http_response_code(200);
				header('Content-Type: application/json');
				$data = array ("Status"=> "OK", "Message" => "Username Available.");
				echo json_encode( $data );
				
			}else{
				http_response_code(401);
				header('Content-Type: application/json');
				$data = array ("Status"=> "ERROR", "Message" => "Username Not Available.");
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