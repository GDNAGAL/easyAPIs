<?php
// Path : api/Dashboard/getDashboardData

include("../connection.php");
require("../encryption.php");
$allowedOrigins = [
    "http://localhost:3000",
    "https://royalplay.live", // Add additional origins as needed
    // Add more origins as needed
];

// Check if the incoming request's origin is in the allowed list
if (isset($_SERVER['HTTP_ORIGIN']) && in_array($_SERVER['HTTP_ORIGIN'], $allowedOrigins)) {
    header("Access-Control-Allow-Origin: " . $_SERVER['HTTP_ORIGIN']);
    header('Access-Control-Allow-Credentials: true');
    header('Access-Control-Max-Age: 86400');
	header("Access-Control-Allow-Headers: Authorization");
}

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