<?php
header("Content-type:application/json");
$_SERVER['CONTENT_TYPE'] = "application/x-www-form-urlencoded"; 
error_reporting (E_ALL ^ E_WARNING && E_NOTICE);

//Response class
class GetAttendanceResponse {
    var $successful;
    var $attendance;
    function __construct () {
        $this->successful = false;
        $this->attendance = -1;
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

//Run query for getting attendance
$getAttendanceQuery = "SELECT attendance FROM db_classroom_management.tbl_room_status WHERE room_id =".$roomID . " AND course_id =" . $courseID;
$getAttendanceQueryResult = mysqli_query($conn, $getAttendanceQuery);

//Handle get attendance response
$numberOfRows = mysqli_num_rows($getAttendanceQueryResult);
$response = new GetAttendanceResponse();

// If the query returned a row, set the attendance accordingly. If the query returned no rows, 
// this means that the table has not been updated and the default attendance needs to be send (-1)
if ($numberOfRows == 0) {
    $response->successful = true;
    $response->attendance = -1;
} else {
    $response->successful = true;
    $row = mysqli_fetch_assoc($getAttendanceQueryResult);
    $response->attendance = $row["attendance"];
}

echo json_encode($response);
?>