<?php
header("Content-type:application/json");
$_SERVER['CONTENT_TYPE'] = "application/x-www-form-urlencoded"; 
error_reporting (E_ALL ^ E_WARNING && E_NOTICE);

//Response class
class AddAttendanceResponse {
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
$attendance = $_POST["attendance"];


//Run query for schedule ovverride
$addAttendanceQuery = "UPDATE db_classroom_management.tbl_room_status set attendance =".$attendance." where room_id =".$roomID." AND course_id =".$courseID;
$addAttendanceQueryResult = mysqli_query($conn, $addAttendanceQuery);

//Handle schedule ovverride response
$numberOfRows = mysqli_num_rows($addAttendanceQueryResult);
$response = new AddAttendanceResponse();


//Check if query worked properly
if ($addAttendanceQueryResult == true) {
    $response->successful = true;  
} else {
    $response->roomIsActive = false;
}

echo json_encode($response); 
?>