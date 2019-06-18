<?php
header("Content-type:application/json");
$_SERVER['CONTENT_TYPE'] = "application/x-www-form-urlencoded"; 
error_reporting (E_ALL ^ E_WARNING && E_NOTICE);

//Response class
class DemoStartResponse {
    var $successful;
    function __construct () {
        $this->successful = false;
    }
}

//Connection properties 
$servername = "localhost";
$username = "root";
$password = "admin";

//Create connection
$conn = new mysqli($servername, $username, $password);

//Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

//Extract data from POST
$roomID = $_POST["roomID"];
$courseID = $_POST["courseID"];
$relayUsed = $_POST["relayUsed"];
$slot = $_POST["slot"];
$attendance = $_POST["attendance"];



//Run query for inserting values to room status table for demo
$demoPreStartQuery = "TRUNCATE db_classroom_management.tbl_room_status";
$demoPreStartQueryReult = mysqli_query($conn, $demoPreStartQuery);
$demoStartQuery = "INSERT INTO  db_classroom_management.tbl_room_status values (".$roomID.",".$courseID.",".$relayUsed.",".$attendance.",curdate(),". $slot.")";
$demoStartQueryResult = mysqli_query($conn, $demoStartQuery);

//Handle inserting values to room status table for demo response
$numberOfRows = mysqli_num_rows($demoStartQueryResult);
$response = new DemoStartResponse();


//Check if query worked properly
if ($demoStartQueryResult == true) {
    $response->successful = true;  
} else {
    $response->roomIsActive = false;
}

echo json_encode($response);
?>