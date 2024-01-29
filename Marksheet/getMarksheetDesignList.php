<?php
// Path : api/ClassRooms/getClassRoomList
include("../connection.php");

if ($_SERVER['REQUEST_METHOD'] == 'POST'){
	$headers = getallheaders();
	if (array_key_exists('Authorization', $headers) && preg_match('/Bearer\s(\S+)/', $headers['Authorization'], $matches)){

		if(verifyToken($matches[1])){
            $sid = getSchoolID($matches[1]);
			$marksheetDesign = mysqli_query($conn, "SELECT * FROM `marksheetdesign` WHERE SchoolID = '$sid'");
			http_response_code(200);
			header('Content-Type: application/json');
			if(mysqli_num_rows($marksheetDesign)>0){
				while($row = mysqli_fetch_assoc($marksheetDesign)) {
						$records[] = $row;
					}
				$data = array ("Status"=> "OK","Message" => "Success", "MarksheetDesignList" => $records);
				echo json_encode( $data );
			}else{
				$data = array ("Status"=> "NOT_FOUND","Message" => "No Design Found");
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