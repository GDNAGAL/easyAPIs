<?php
// Path : 
include("../../connection.php");

if ($_SERVER['REQUEST_METHOD'] == 'POST'){
	$headers = getallheaders();
	if (array_key_exists('Authorization', $headers) && preg_match('/Bearer\s(\S+)/', $headers['Authorization'], $matches)){

		if(verifyToken($matches[1])){
		

			$classGroupList = mysqli_query($conn, "SELECT * FROM `defaultclassroomgroups` Order By ClassRoomGroupID");

			http_response_code(200);
			header('Content-Type: application/json');
			if(mysqli_num_rows($classGroupList)>0){
				while($row = mysqli_fetch_assoc($classGroupList)) {
					$ClassRoomGroupID = $row['ClassRoomGroupID'];
					$row['ClassRoomList'] = [];
					$classList = mysqli_query($conn, "SELECT * FROM `defaultclassrooms` WHERE ClassRoomGroupID = $ClassRoomGroupID");
					while($crow = mysqli_fetch_assoc($classList)){
						$row['ClassRoomList'][] = $crow;
					}
					$records[] = $row;
					}
				$data = array ("Status"=> "OK","Message" => "Success", "ClassRoomGroupList" => $records);
				echo json_encode( $data );
			}else{
				$data = array ("Status"=> "NOT_FOUND","Message" => "No ClassRoom Group Found");
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