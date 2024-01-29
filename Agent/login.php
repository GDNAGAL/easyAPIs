<?php
include("connection.php");
require("encryption.php");
date_default_timezone_set("Asia/Calcutta");
//Validate Login
if(isset($_POST['login'])){
	$myusername = mysqli_real_escape_string($conn,$_POST['username']);
    $mypassword = mysqli_real_escape_string($conn,$_POST['password']); 

    $result = mysqli_query($conn, "SELECT AgentID, AgentName FROM `agents` WHERE `AgentMobile` = '$myusername' AND `Password` = '$mypassword'");
    if (mysqli_num_rows($result)==1) {
		$row = mysqli_fetch_assoc($result);

		$loginDateTime = date("Y-m-d h:m:s"); 
		$agentArray = [];
		$agentArray[] = $row;
		$accesstoken = encrypt(json_encode($agentArray));
        $AgentID = $row['AgentID'];
		mysqli_query($conn, "UPDATE `admin_login_log` SET `Token`='Session Log Out' WHERE `AgentID`='$AgentID'");
		mysqli_query($conn, "INSERT INTO `admin_login_log`(`AgentID`, `Token`, `LoginDateTime`) VALUES ('$AgentID','$accesstoken','$loginDateTime')");

		http_response_code(200);
		header('Content-Type: application/json');
		$data = array ("Status" => "Success", "Message" => "Login Success", "Token" => $accesstoken,);
		echo json_encode( $data );

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