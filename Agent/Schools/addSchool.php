<?php
// Path : api/ClassRooms/addClassRoom
include("../connection.php");

if ($_SERVER['REQUEST_METHOD'] == 'POST'){
	$headers = getallheaders();
	if (array_key_exists('Authorization', $headers) && preg_match('/Bearer\s(\S+)/', $headers['Authorization'], $matches)){

		if(verifyToken($matches[1])){
            $agentid = getAgentID($matches[1]);
			$schoolName = $_POST['schoolName'];
			$schoolAddress = $_POST['schoolAddress'];
			$HMName = $_POST['HMName'];
			$schoolMobile = $_POST['schoolMobile'];
			$today = date('Y-m-d');
			$checkDuplicateSchool = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as sn FROM `schools` WHERE SchoolHeadMobile = '$schoolMobile'"));
			if($checkDuplicateSchool['sn'] == 0){
				$addSchool = mysqli_query($conn, "INSERT INTO `schools`(`SchoolName`, `SchoolAddress`, `SchoolHeadName`, `SchoolHeadMobile`, `SchoolRegDate`, `CurrentYear`, `SchoolUserName`, `SchoolPassword`, `SchoolStatus`, `SchoolAddedBy`)  VALUES ('$schoolName','$schoolAddress','$HMName','$schoolMobile','$today','2023','$schoolMobile','$schoolMobile','2','$agentid')");
				
				http_response_code(200);
				header('Content-Type: application/json');
				if($addSchool == TRUE){
					$data = array ("Status"=> "OK", "Message" => "School Added Successfully.");
					echo json_encode( $data );
				}else{
					$data = array ("Status"=> "ERROR","Message" => "Failed");
					echo json_encode( $data );
				}
			}else{
				http_response_code(200);
				header('Content-Type: application/json');
				$data = array ("Status"=> "ERROR", "Message" => "Mobile No. Already Registred.");
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