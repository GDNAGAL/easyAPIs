<?php
include("connection.php");
require("encryption.php");
date_default_timezone_set("Asia/Calcutta");
//Validate Login
if(isset($_POST['login'])){
	$myusername = mysqli_real_escape_string($conn,$_POST['username']);
    $mypassword = mysqli_real_escape_string($conn,$_POST['password']); 

    $result = mysqli_query($conn, "SELECT * FROM `schools` LEFT JOIN school_status ON schools.SchoolStatus = school_status.SchoolStatusID  WHERE `SchoolUserName` = '$myusername' AND `SchoolPassword` = '$mypassword'");
    if (mysqli_num_rows($result)==1) {
		$row = mysqli_fetch_assoc($result);
		if($row['SchoolStatus'] == 1){
			$loginDateTime = date("Y-m-d h:m:s"); 
			$schoolArray = [];
			$schoolArray[] = $row;
			$accesstoken = encrypt(json_encode($schoolArray));
			$schoolID = $row['SchoolID'];
			mysqli_query($conn, "UPDATE `login_logs` SET `Token`='Session Log Out' WHERE `SchoolID`='$schoolID'");
			mysqli_query($conn, "INSERT INTO `login_logs`(`SchoolID`, `Token`, `LoginDateTime`) VALUES ('$schoolID','$accesstoken','$loginDateTime')");
			
			http_response_code(200);
			header('Content-Type: application/json');
			$data = array ("Status" => "Success", "Message" => "Login Success", "Token" => $accesstoken);
			echo json_encode( $data );
		}else{
			http_response_code(401);
			header('Content-Type: application/json');
			$data = array ("Status" => "Success", "Message" => $row['StatusText']);
			echo json_encode( $data );
		}

	}else{

		http_response_code(401);
		header('Content-Type: application/json');
		$data = array ("Status" => "Failed", "Message" => "Wrong UserName And Password");
		echo json_encode( $data );

	}
	
}else{

	http_response_code(401);
    header('Content-Type: application/json');
    $data = array ("Message" => "UnAuthorized Access");
    echo json_encode( $data );
	
}

?>