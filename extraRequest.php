<?php
header("Content-type:application/json");
$_SERVER['CONTENT_TYPE'] = "application/x-www-form-urlencoded"; 
error_reporting (E_ALL ^ E_WARNING && E_NOTICE);

//Response class
class AddAttendanceResponse {
    var $successful;
    var $moreThanMaxStudents;
    function __construct () {
        $this->successful = false;
        $this->moreThanMaxStudents = true;
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
$requestType = $_POST["requestType"];
$userID = $_POST["userID"];
$courseID = $_POST["courseID"];
$roomID = $_POST["roomID"];
$dayOfWeek = $_POST["dayOfWeek"];
$slot = $_POST["slot"];
$length = $_POST["length"];
$generalReason = $_POST["generalReason"];
$message = $_POST["message"];

if ($requestType == "CANCEL") {
    // First we need to check if the attendance entered by the user is less than or equals to total students enrolled
    $getStudentsEnrolledQuery = "SELECT total_students_enrolled FROM db_classroom_management.tbl_courses WHERE course_id = " . $courseID;
    $getStudentsEnrolledQueryResult = mysqli_query($conn, $getStudentsEnrolledQuery);
    //Handle query for max students enrolled
    $numberOfRows = mysqli_num_rows($getStudentsEnrolledQueryResult);
    $goAhead = false;
    if ($numberOfRows >= 1) {
        $row = mysqli_fetch_assoc($getStudentsEnrolledQueryResult);
        $maxStudentsAllowed = intval($row['total_students_enrolled']);
        $goAhead = true;
    }
}


?>