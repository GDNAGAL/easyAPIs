<?php
// Path : api/getLoginUserData

include("connection.php");
require("encryption.php");

if ($_SERVER['REQUEST_METHOD'] == 'GET'){
	$headers = getallheaders();
	if (array_key_exists('Authorization', $headers) && preg_match('/Bearer\s(\S+)/', $headers['Authorization'], $matches)){

		
		if(verifyToken($matches[1])){
			$data = decrypt(json_encode($matches[1]));
			echo $data;
		}else{
			echo "Invalid Token"; 
		}

	}else{
		echo "Invalid Token";
	}
}

?>