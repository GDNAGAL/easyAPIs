<?php
// Path : 
include("../connection.php");

if ($_SERVER['REQUEST_METHOD'] == 'POST'){
	$headers = getallheaders();
	if (array_key_exists('Authorization', $headers) && preg_match('/Bearer\s(\S+)/', $headers['Authorization'], $matches)){

		if(verifyToken($matches[1])){
			$ClassRoomGroupID = $_POST['ClassRoomGroupID'];

			$subjectlist = mysqli_query($conn, "SELECT * FROM `defaultsubjects` inner join subject_types on defaultsubjects.SubjectTypeID = subject_types.SubjectTypeID Where ClassRoomGroupID = '$ClassRoomGroupID'");

			http_response_code(200);
			header('Content-Type: application/json');
			if(mysqli_num_rows($subjectlist)>0){
				while($row = mysqli_fetch_assoc($subjectlist)) {
					$records[] = $row;
					}
				$data = array ("Status"=> "OK","Message" => "Success", "SubjectList" => $records);
				echo json_encode( $data );
			}else{
				$data = array ("Status"=> "NOT_FOUND","Message" => "No Subject Found");
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