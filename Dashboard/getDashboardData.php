<?php
// Path : api/Dashboard/getDashboardData

include("../connection.php");
require("../encryption.php");

if ($_SERVER['REQUEST_METHOD'] == 'GET'){
	$headers = getallheaders();
	if (array_key_exists('Authorization', $headers) && preg_match('/Bearer\s(\S+)/', $headers['Authorization'], $matches)){

		if(verifyToken($matches[1])){
			$data = decrypt(json_encode($matches[1]));

			// Call the stored procedure
			$sid = getSchoolID($matches[1]);
			$getDashboardData = mysqli_fetch_assoc(mysqli_query($conn,"CALL getDashboardData($sid)"));

			http_response_code(200);
			header('Content-Type: application/json');
			$data = array ("Status" => "Success", "DashboardData" => $getDashboardData);
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