<?php
header("Content-type:application/json");
$_SERVER['CONTENT_TYPE'] = "application/x-www-form-urlencoded"; 
error_reporting (E_ALL ^ E_WARNING && E_NOTICE);

// cold initialization
$requestType = "";
$userID = "";
$courseID = "";
$roomID = "";
$dayOfWeek = "";
$slot = "";
$length = "";
$generalReason = "";
$message = "";

//Response class
class ExtraRequestResponse {
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
/*
$requestType = $_POST["requestType"];
$userID = $_POST["userID"];
$courseID = $_POST["courseID"];
$roomID = $_POST["roomID"];
$dayOfWeek = $_POST["dayOfWeek"];
$slot = $_POST["slot"];
$length = $_POST["length"];
*/
$requestType = $_POST["requestType"];
$generalReason = $_POST["generalReason"];
$message = $_POST["message"];


if ($requestType == "CANCEL") {
    
    // Get variables from post
    $userID = $_POST["userID"];
    $courseID = $_POST["courseID"];
    $dayOfWeek = $_POST["dayOfWeek"];
    $slot = $_POST["slot"];
    $length = $_POST["length"];

    // Check if the class exists
    $getClassNormal = "SELECT * FROM db_classroom_management.view_Normal_schedule WHERE teacher_id = ". $userID . " AND course_id = " . $courseID . " AND slot = " . $slot . " AND day_of_week = " . $dayOfWeek .  " AND class_length = " . $length;
    $normalResult = mysqli_query($conn, $getClassNormal);
    $normalNo = mysqli_num_rows($normalResult);

    $getClassExtra = "SELECT * FROM db_classroom_management.view_extra_schedule WHERE teacher_id = ". $userID . " AND course_id = " . $courseID . " AND slot = " . $slot . " AND day_of_week = " . $dayOfWeek .  " AND class_length = " . $length;
    $extraResult = mysqli_query($conn, $getClassExtra);
    $extraNo = mysqli_num_rows($extraResult);

    // Variable that will determine if the reuqest is for an extra class or for a normal class
    $isExtra = false;
    $scheduleIDToCancel = 0;

    if ($normalNo > 0) {
        $isExtra = false;
        $row = mysqli_fetch_assoc($normalResult);
        $scheduleIDToCancel = $row['schedule_id'];
        $roomID = $row['room_id'];
    } else if ($normalNo > 0) {
        $isExtra = true;
        $row = mysqli_fetch_assoc($extraResult);
        $scheduleIDToCancel = $row['extra_schedule_id'];
        $roomID = $row['room_id'];
    } else {
        echo ("Does not exist\n");
        echo ($getClassNormal);
        // TODO: Class does not exist
    }

    // Check if the class is already cancelled (or requested to be cancelled) or not by checking for the same class in extra schedule
    $alreadyCancelCheck = "SELECT * FROM db_classroom_management.view_extra_schedule_without_where WHERE teacher_id = ". $userID . " AND course_id" . $courseID . " AND slot = " . $slot . " AND day_of_week = " . $dayOfWeek .  " AND class_length = " . $length . " AND request_type = 'CANCEL'";
    $cancelCheckResult = mysqli_query($conn, $alreadyCancelCheck);
    $checkNo = mysqli_num_rows($cancelCheckResult);
    if ($checkNo > 0) {
        echo ("Already cancelled");
        // TODO: the class has already been cancelled
    }

    // Submit the request
    $insertCancel = "INSERT INTO db_classroom_management.tbl_extra_schedule (request_type, requestor, course_id, room_id, general_reason, message) VALUES ("
    . "'" . $requestType . "'," . $userID . "," . $courseID . "," . $roomID  . ",'" . $generalReason . "','" . $message . "');";
    $cancelResult = mysqli_query($conn, $insertCancel);
    if ($cancelResult == true)
        echo ("done");
    else {
        echo ($insertCancel);
        echo ("\nNumber of rows: " . mysqli_num_rows($cancelResult));
        echo ("\nna-aan");
    }
}


?>