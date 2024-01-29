<?php
$type = "TESTDB";   //LIVEDB OR TESTDB OR LOCALDB

if($type == "LOCALDB"){

  $servername = "localhost";
  $username = "root";
  $password = "";
  $db = "easy";

}elseif($type == "TESTDB"){

  $servername = "154.41.233.103";
  $username = "u664437076_easy";
  $password = "JY6o8n1T@p";
  $db = "u664437076_easy";

}elseif($type == "LIVEDB"){

  $servername = "localhost";
  $username = "root";
  $password = "";
  $db = "easy";

}

  header('Access-Control-Allow-Origin: *');
  header('Access-Control-Allow-Methods: POST, GET, DELETE, PUT, PATCH, OPTIONS');
  header('Access-Control-Allow-Headers: token, Content-Type');
  header('Access-Control-Max-Age: 1728000');
  header('Content-Length: 0');
  header('Content-Type: text/plain');
  header('Access-Control-Allow-Origin: *');
  header('Content-Type: application/json');
  die();


// Create connection
$conn = new mysqli($servername, $username, $password, $db);

function verifyToken($token){
  global $conn;
  $verifyToken =   mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS loginUser FROM `login_logs` WHERE Token = '$token'"));
  $log = $verifyToken['loginUser'];
  if($log == 1){
    return true;
  }else{
    echo $log;
    return false;
  }
}

function getSchoolID($token){
  global $conn;
  $verifyToken =   mysqli_fetch_assoc(mysqli_query($conn, "SELECT SchoolID  FROM `login_logs` WHERE Token = '$token'"));
  $SchoolID = $verifyToken['SchoolID'];
  return $SchoolID;
}

// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}
//echo "Connected successfully";


?>