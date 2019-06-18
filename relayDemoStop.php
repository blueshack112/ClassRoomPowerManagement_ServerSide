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

//Run query for inserting values to room status table for demo
$demoStopQuery = "TRUNCATE db_classroom_management.tbl_room_status";
$demoStopQueryReult = mysqli_query($conn, $demoStopQuery);

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