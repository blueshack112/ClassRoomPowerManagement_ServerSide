<?php
header("Content-type:application/json");
$_SERVER['CONTENT_TYPE'] = "application/x-www-form-urlencoded"; 
error_reporting (E_ALL ^ E_WARNING && E_NOTICE);

//Response class
class AddAttendanceResponse {
    var $successful;
    var $moreThanMaxStudents;
    var $alreadyEntered;
    var $attendanceInTable;
    function __construct () {
        $this->successful = false;
        $this->moreThanMaxStudents = true;
        $this->alreadyEntered = true;
        $this->attendanceInTable = -1;
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

// Check if attendance already entered or not
$getAttendanceQuery = "SELECT attendance FROM db_classroom_management.tbl_room_status WHERE room_id =".$roomID . " AND course_id =" . $courseID;
$getAttendanceQueryResult = mysqli_query($conn, $getAttendanceQuery);
$numberOfRows = mysqli_num_rows($getAttendanceQueryResult);

if ($numberOfRows >= 1) {
    $row = mysqli_fetch_assoc($getAttendanceQueryResult);
    $currentAttendance = intval($row['attendance']);
    
    // If attendnace is already entered. Send the appropreate response    
    if ($currentAttendance >= 0) {
        $response = new AddAttendanceResponse();
        $response->successful = true;
        $response->moreThanMaxStudents = false;
        $response->alreadyEntered = true;
        $response->attendanceInTable = $currentAttendance;
        echo json_encode($response); 
        return;
    }
    $goAhead = true;
} else {
    $goAhead = false;
}

// Send the result if the attendance entered by user is less than or equal to the max students enrolled in that course
$response = new AddAttendanceResponse();
if ($goAhead && intval($attendance) <= $maxStudentsAllowed) {
    //Run query for updating attendance
    $addAttendanceQuery = "UPDATE db_classroom_management.tbl_room_status set attendance = " . $attendance . " where room_id = " . $roomID . " AND course_id = " . $courseID;
    $addAttendanceQueryResult = mysqli_query($conn, $addAttendanceQuery);

    //Handle attendance update
    $numberOfRows = mysqli_num_rows($addAttendanceQueryResult);

    //Check if query worked properly
    if ($addAttendanceQueryResult == true) {
        $response->successful = true;
        $response->moreThanMaxStudents = false;
        $response->alreadyEntered = false;
        $response->attendanceInTable = $attendance;
    } else {
        $response->successful = false;
    }
    echo json_encode($response); 
} else {
    $response->successful = true;
    $response->moreThanMaxStudents = true;
    $response->alreadyEntered = false;
    echo json_encode($response);
}
?>