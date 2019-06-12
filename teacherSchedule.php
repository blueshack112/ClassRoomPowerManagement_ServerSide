<?php
header("Content-type:application/json");
$_SERVER['CONTENT_TYPE'] = "application/x-www-form-urlencoded"; 
error_reporting (E_ALL ^ E_WARNING && E_NOTICE);

//
class ScheduleItem {
    var $courseName;
    var $teacherID;
    var $roomID;
    var $courseID;
    var $dayOfWeek;
    var $slot;
    var $classLength;
}

//response class
class SchedResponse {
    var $scheduleFound;
    var $scheduleItems;

    function __construct () {
        $this->scheduleFound = false;
        $this->scheduleItems = array();
    }
}

//Connection properties
$servername = "localhost";
$username = "root";
$password = "admin";

// Create connection
$conn = new mysqli($servername, $username, $password);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

//Extract id and password from GET
$userID = $_POST["userID"];

//Run query for login authorization
$getScheduleQuery = "SELECT * FROM db_classroom_management.view_teacher_schedule where teacher_id=".$userID;
$getScheduleQueryResult = mysqli_query($conn, $getScheduleQuery);

//Handle login authorization response
$numberOfRows = mysqli_num_rows($getScheduleQueryResult);
$response = new SchedResponse;

if ($numberOfRows >= 1) {
    $response->scheduleFound = true;
    while ($row = mysqli_fetch_assoc($getScheduleQueryResult)) {
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
?>