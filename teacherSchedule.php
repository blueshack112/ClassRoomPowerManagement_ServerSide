<?php
header("Content-type:application/json");
$_SERVER['CONTENT_TYPE'] = "application/x-www-form-urlencoded"; 
error_reporting (E_ALL ^ E_WARNING && E_NOTICE);

// Schedule item class
class ScheduleItem {
    var $courseName;
    var $teacherID;
    var $roomID;
    var $courseID;
    var $dayOfWeek;
    var $slot;
    var $classLength;
    var $classAttendance;
    var $isExtra;       // if came from extra schedule table. False if not and True if yes.
}

// Response class
class SchedResponse {
    var $scheduleFound;
    var $scheduleItems;

    function __construct () {
        $this->scheduleFound = false;
        $this->scheduleItems = array();
    }
}

// Connection properties
$servername = "localhost";
$username = "root";
$password = "admin";

// Create connection
$conn = new mysqli($servername, $username, $password);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Extract id and password from GET
$userID = $_POST["userID"];

// Run normal schedule query
$getNormalScheduleQuery = "SELECT * FROM db_classroom_management.view_teacher_normal_schedule where teacher_id=".$userID;
$getNormalScheduleQueryResult = mysqli_query($conn, $getNormalScheduleQuery);

// Handle normal schedule query result
$numberOfRows = mysqli_num_rows($getNormalScheduleQueryResult);
$response = new SchedResponse;
$response->scheduleFound = false;

// Alot the data in response class
if ($numberOfRows >= 1) {
    $response->scheduleFound = true;
    while ($row = mysqli_fetch_assoc($getNormalScheduleQueryResult)) {
        $temp = new ScheduleItem();
        $temp->isExtra = false;
        $temp->courseName = $row['course_name'];
        $temp->teacherID = $row['teacher_id'];
        $temp->roomID = $row['room_id'];
        $temp->courseID = $row['course_id'];
        $temp->dayOfWeek = $row['day_of_week'];
        $temp->slot = $row['slot'];
        $temp->classLength = $row['class_length'];
        $response->scheduleItems[] = $temp;
    }
} else {
    $response->scheduleFound = false;
}

// Run Extra schedule query
$getExtraScheduleQuery = "SELECT * FROM db_classroom_management.view_teacher_extra_schedule where teacher_id=".$userID;
$getExtraScheduleQueryResult = mysqli_query($conn, $getExtraScheduleQuery);

// Handle Extra schedule query result
$numberOfRows = mysqli_num_rows($getExtraScheduleQueryResult);

// Alot the data in response class
if ($numberOfRows >= 1) {
    $response->scheduleFound = true;
    while ($row = mysqli_fetch_assoc($getExtraScheduleQueryResult)) {
        $temp = new ScheduleItem();
        $temp->isExtra = true;
        $temp->courseName = $row['course_name'];
        $temp->teacherID = $row['teacher_id'];
        $temp->roomID = $row['room_id'];
        $temp->courseID = $row['course_id'];
        $temp->dayOfWeek = $row['day_of_week'];
        $temp->slot = $row['slot'];
        $temp->classLength = $row['class_length'];
        $response->scheduleItems[] = $temp;
    }
}

echo json_encode($response);

/*
//Run query for login authorization
$getNormalScheduleQuery = "SELECT * FROM db_classroom_management.view_teacher_schedule where teacher_id=".$userID;
$getNormalScheduleQueryResult = mysqli_query($conn, $getNormalScheduleQuery);

//Handle login authorization response
$numberOfRows = mysqli_num_rows($getNormalScheduleQueryResult);
$response = new SchedResponse;

if ($numberOfRows >= 1) {
    $response->scheduleFound = true;
    while ($row = mysqli_fetch_assoc($getNormalScheduleQueryResult)) {
        $temp = new ScheduleItem();
        $temp->courseName = $row['course_name'];
        $temp->teacherID = $row['teacher_id'];
        $temp->roomID = $row['room_id'];
        $temp->courseID = $row['course_id'];
        $temp->dayOfWeek = $row['day_of_week'];
        $temp->slot = $row['slot'];
        $temp->classLength = $row['class_length'];
        $response->scheduleItems[] = $temp;
    }
} else {
    $response->scheduleFound = false;
}

echo json_encode($response); 
*/
?>