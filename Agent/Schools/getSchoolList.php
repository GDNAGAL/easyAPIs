<?php
// Path : api/Teachers/getTeacherList
include("../connection.php");

if ($_SERVER['REQUEST_METHOD'] == 'POST'){
	$headers = getallheaders();
	if (array_key_exists('Authorization', $headers) && preg_match('/Bearer\s(\S+)/', $headers['Authorization'], $matches)){

		if(verifyToken($matches[1])){
            $sid = getAgentID($matches[1]);

			$schoolslist = mysqli_query($conn, "SELECT * FROM `schools` LEFT JOIN school_status ON schools.SchoolStatus = school_status.SchoolStatusID");

			http_response_code(200);
			header('Content-Type: application/json');
			if(mysqli_num_rows($schoolslist)>0){
				while($row = mysqli_fetch_assoc($schoolslist)) {
					$datedata = $row['SchoolRegDate'];
					$date = new DateTime($datedata);
					$row['SchoolRegDate'] = $date->format('d-m-Y');
					$records[] = $row;
					}
				$data = array ("Status"=> "OK","Message" => "Success", "SchoolList" => $records);
				echo json_encode( $data );
			}else{
				$data = array ("Status"=> "NOT_FOUND","Message" => "No Teachers Found.");
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