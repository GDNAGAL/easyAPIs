<?php
// Path : 
include("../connection.php");

if ($_SERVER['REQUEST_METHOD'] == 'POST'){
	$headers = getallheaders();
	if (array_key_exists('Authorization', $headers) && preg_match('/Bearer\s(\S+)/', $headers['Authorization'], $matches)){

		if(verifyToken($matches[1])){
			$SubjectID = $_POST['SubjectID'];

			$examlist = mysqli_query($conn, "SELECT * FROM `defaultexams` WHERE ExamID IN (SELECT Distinct ExamIndex FROM `defaultpaper` WHERE SubjectIndex = '$SubjectID')");
			http_response_code(200);
			header('Content-Type: application/json');
			if(mysqli_num_rows($examlist)>0){
				while($row = mysqli_fetch_assoc($examlist)) {
						$ExamID = $row['ExamID'];
						$row['PaperList'] = [];
						$paperListq = mysqli_query($conn, "SELECT * FROM `defaultpaper` WHERE ExamIndex = '$ExamID' AND SubjectIndex = '$SubjectID'");
						while($paperRow = mysqli_fetch_assoc($paperListq)){
							$row['PaperList'][] = $paperRow;
						}
					$records[] = $row;
					}
				$data = array ("Status"=> "OK","Message" => "Success", "ExamList" => $records);
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