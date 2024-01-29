<?php
// Path : api/ClassRooms/addClassRoom
include("../connection.php");

if ($_SERVER['REQUEST_METHOD'] == 'POST'){
	$headers = getallheaders();
	if (array_key_exists('Authorization', $headers) && preg_match('/Bearer\s(\S+)/', $headers['Authorization'], $matches)){

		if(verifyToken($matches[1])){
            $agentid = getAgentID($matches[1]);
			$SchoolName = $_POST['SchoolName'];
			$SchoolID = $_POST['SchoolID'];
			$SchoolAddress = $_POST['SchoolAddress'];
			$SchoolHeadName = $_POST['SchoolHeadName'];
			$SchoolHeadMobile = $_POST['SchoolHeadMobile'];
			$SchoolUserName = $_POST['SchoolUserName'];
			$SchoolPassword = $_POST['SchoolPassword'];
			$SchoolStatus = $_POST['SchoolStatus'];

			$updateSchool = mysqli_query($conn, "UPDATE `schools` SET `SchoolName`='$SchoolName',`SchoolAddress`='$SchoolAddress',`SchoolHeadName`='$SchoolHeadName',`SchoolHeadMobile`='$SchoolHeadMobile',`SchoolUserName`='$SchoolUserName',`SchoolPassword`='$SchoolPassword',`SchoolStatus`='$SchoolStatus' WHERE SchoolID = '$SchoolID'");
			if($updateSchool == TRUE){

				http_response_code(200);
				header('Content-Type: application/json');
				$data = array ("Status"=> "OK", "Message" => "School Data Updated Successfull");
				echo json_encode( $data );
				
			}else{
				http_response_code(401);
				header('Content-Type: application/json');
				$data = array ("Status"=> "ERROR", "Message" => "Failed To Update");
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