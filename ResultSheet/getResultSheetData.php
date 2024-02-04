<?php
// Path : api/Students/getStudentList
include("../connection.php");

if ($_SERVER['REQUEST_METHOD'] == 'POST'){
	$headers = getallheaders();
	if (array_key_exists('Authorization', $headers) && preg_match('/Bearer\s(\S+)/', $headers['Authorization'], $matches)){

		if(verifyToken($matches[1])){
            $sid = getSchoolID($matches[1]);
			$selectSchool = mysqli_fetch_assoc(mysqli_query($conn, "SELECT SchoolName,SchoolAddress FROM `schools` WHERE SchoolID = '$sid' limit 1"));
			$SectionID = $_POST['SectionID'];
			$StudentID = $_POST['StudentID'];
			if ($StudentID == 'all') {
				$selectstudentlist = mysqli_query($conn, "SELECT StudentID,StudentName,Gender,StudentFatherName,StudentMotherName,DateofBirth,students.ClassRoomID,students.SectionID,classrooms.ClassIndex,AdmissionNo,RollNo,ClassRoomName,SectionText,StudentPhoto FROM `students` INNER JOIN `classrooms` ON students.ClassRoomID = classrooms.ClassRoomID INNER JOIN `classrooms_sections` ON students.SectionID = classrooms_sections.SectionID  WHERE students.SchoolID = '$sid' AND students.SectionID = '$SectionID'");
			}else{
				$selectstudentlist = mysqli_query($conn, "SELECT StudentID,StudentName,Gender,StudentFatherName,StudentMotherName,DateofBirth,students.ClassRoomID,students.SectionID,classrooms.ClassIndex,AdmissionNo,RollNo,ClassRoomName,SectionText,StudentPhoto FROM `students` INNER JOIN `classrooms` ON students.ClassRoomID = classrooms.ClassRoomID INNER JOIN `classrooms_sections` ON students.SectionID = classrooms_sections.SectionID  WHERE students.SchoolID = '$sid' AND students.SectionID = '$SectionID' AND StudentID = '$StudentID'");
			}

			http_response_code(200);
			header('Content-Type: application/json');
			if(mysqli_num_rows($selectstudentlist)>0){
				while($row = mysqli_fetch_assoc($selectstudentlist)) {
					$row['StudentPhoto'] = base64_encode($row['StudentPhoto']);
					$ClassRoomID = $row['ClassRoomID'];
					$StudentIDs = $row['StudentID'];
					$row['ClassRoomName'] = $row['ClassRoomName'].' '. $row['SectionText'];
					$dateofbirth = $row['DateofBirth'];
					$date = new DateTime($dateofbirth);
					$row['DateofBirth'] = $date->format('d-m-Y');
					$row['COMSubjects'] = [];
					$row['OPTSubjects'] = [];

					$selectSubject = mysqli_query($conn, "SELECT Distinct ed.SubjectID, su.SubjectName FROM examdesign ed JOIN subjects su ON ed.SubjectID = su.SubjectID WHERE ed.ClassRoomID = '$ClassRoomID' AND SubjectTypeID = 1");
					while($subjectRow = mysqli_fetch_assoc($selectSubject)){
						$subjectID = $subjectRow['SubjectID'];
						$subjectRow['Exams'] = [];
						$examName = mysqli_query($conn, "SELECT ExamID,ExamText FROM exams WHERE ExamID IN (SELECT DISTINCT ExamID FROM `examdesign` WHERE ClassRoomID = '$ClassRoomID' AND SubjectID = '$subjectID')");
						while($examNameRow = mysqli_fetch_assoc($examName)){
							$ExamID = $examNameRow['ExamID'];
							$examNameRow['Papers'] = [];
							
							$paper = mysqli_query($conn, "SELECT PaperID,PaperDisplayText,PaperMM FROM `examdesign` WHERE ExamID = '$ExamID' AND ClassRoomID = '$ClassRoomID' AND SubjectID = '$subjectID'");
							while($paperRow = mysqli_fetch_assoc($paper)){
								$PaperID = $paperRow['PaperID'];
								
								$marks = mysqli_fetch_assoc(mysqli_query($conn, "SELECT MarksObtained FROM `student_paper_marks` WHERE PaperID = '$PaperID' AND StudentID = '$StudentIDs' AND SubjectID = '$subjectID'"));
								$paperRow['MarksObtained'] = $marks['MarksObtained'];
								$examNameRow['Papers'][] = $paperRow;
							}

							$subjectRow['Exams'][] = $examNameRow;
						}
						$row['COMSubjects'][] = $subjectRow;
					}


					$OptselectSubject = mysqli_query($conn, "SELECT Distinct ed.SubjectID, su.SubjectName FROM examdesign ed JOIN subjects su ON ed.SubjectID = su.SubjectID WHERE ed.ClassRoomID = '$ClassRoomID' AND SubjectTypeID = 2");
					while($subjectRow = mysqli_fetch_assoc($OptselectSubject)){
						$subjectID = $subjectRow['SubjectID'];
						$subjectRow['Exams'] = [];
						$examName = mysqli_query($conn, "SELECT ExamID,ExamText FROM exams WHERE ExamID IN (SELECT DISTINCT ExamID FROM `examdesign` WHERE ClassRoomID = '$ClassRoomID' AND SubjectID = '$subjectID')");
						while($examNameRow = mysqli_fetch_assoc($examName)){
							$ExamID = $examNameRow['ExamID'];
							$examNameRow['Papers'] = [];
							
							$paper = mysqli_query($conn, "SELECT PaperID,PaperDisplayText,PaperMM FROM `examdesign` WHERE ExamID = '$ExamID' AND ClassRoomID = '$ClassRoomID' AND SubjectID = '$subjectID'");
							while($paperRow = mysqli_fetch_assoc($paper)){
								$PaperID = $paperRow['PaperID'];
								
								$marks = mysqli_fetch_assoc(mysqli_query($conn, "SELECT MarksObtained FROM `student_paper_marks` WHERE PaperID = '$PaperID' AND StudentID = '$StudentIDs' AND SubjectID = '$subjectID'"));
								$paperRow['MarksObtained'] = $marks['MarksObtained'];
								$examNameRow['Papers'][] = $paperRow;
							}

							$subjectRow['Exams'][] = $examNameRow;
						}
						$row['OPTSubjects'][] = $subjectRow;
					}







					$records[] = $row;
					}
				$data = array ("Status"=> "OK","Message" => "Success", "StudentList" => $records, "School"=>$selectSchool);
				echo json_encode( $data );
			}else{
				$data = array ("Status"=> "NOT_FOUND","Message" => "No Student Found");
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