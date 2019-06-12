<?php
header("Content-type:application/json");
$_SERVER['CONTENT_TYPE'] = "application/x-www-form-urlencoded"; 
error_reporting (E_ALL ^ E_WARNING && E_NOTICE);

//Response object
class RoomStatus {
    var $classDate;
    var $roomID;
    var $courseName;
    var $teacherFirstName;
    var $teacherLastName;
    var $classLength;
    var $attendance;

    function __construct($roomID)
    {
        $this->classDate = "";
        $this->roomID = $roomID;
        $this->courseName = "";
        $this->teacherFirstName = "";
        $this->teacherLastName = "";
        $this->classLength = 0;
        $this->attendance = 0;
    }
}

//Response class
class RoomStatusResponse {
    var $roomIsActive;
    var $roomResponse;

    function __construct ($roomID) {
        $this->roomIsActive = false;
        $this->roomResponse = new RoomStatus($roomID);
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

//Extract roomID from POST
$roomID = $_POST["roomID"];

//Run query for room status checking
$roomStatusQuery = "SELECT * FROM db_classroom_management.view_room_status where room_id=".$roomID;
$roomStatusQueryResult = mysqli_query($conn, $roomStatusQuery);

//Handle room status response
$numberOfRows = mysqli_num_rows($roomStatusQueryResult);
$response = new RoomStatusResponse($roomID);

if ($numberOfRows >= 1) {
    $response->roomIsActive = true;
    while ($row = mysqli_fetch_assoc($roomStatusQueryResult)) {
        $temp = new RoomStatus($roomID);
        $temp->classDate = $row['class_date'];
        $temp->roomID = $row['room_id'];
        $temp->courseName = $row['course_name'];
        $temp->teacherFirstName = $row['teacher_first_name'];
        $temp->teacherLastName = $row['teacher_last_name'];
        $temp->classLength = $row['class_length'];
        $temp->attendance = $row['attendance'];
        if ($temp->attendance == 0) {
            $temp->attendance = -1;
        }
        $response->roomResponse = $temp;
    }
} else {
    $response->roomIsActive = false;
}

echo json_encode($response); 
?>