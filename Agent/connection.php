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
// Create connection
$conn = new mysqli($servername, $username, $password, $db);

function verifyToken($token){
  global $conn;
  $verifyToken =   mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS loginUser FROM `admin_login_log` WHERE Token = '$token'"));
  $log = $verifyToken['loginUser'];
  if($log == 1){
    return true;
  }else{
    //echo $log;
    return false;
  }
}

function getAgentID($token){
  global $conn;
  $verifyToken =   mysqli_fetch_assoc(mysqli_query($conn, "SELECT AgentID  FROM `admin_login_log` WHERE Token = '$token'"));
  $AgentID = $verifyToken['AgentID'];
  return $AgentID;
}

// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}
//echo "Connected successfully";


?>