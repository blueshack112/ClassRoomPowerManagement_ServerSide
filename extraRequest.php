<?php
header("Content-type:application/json");
$_SERVER['CONTENT_TYPE'] = "application/x-www-form-urlencoded"; 
error_reporting (E_ALL ^ E_WARNING && E_NOTICE);

//cold initialization
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
        return;
        // TODO: Class does not exist
    }

    // Check if the class is already cancelled (or requested to be cancelled) or not by checking for the same class in extra schedule
    $alreadyCancelCheck = "SELECT * FROM db_classroom_management.tbl_extra_schedule WHERE requestor = ". $userID . " AND course_id = " . $courseID . " AND slot = " . $slot . " AND day_of_week = " . $dayOfWeek .  " AND class_length = " . $length . " AND request_type = 'CANCEL'";
    $cancelCheckResult = mysqli_query($conn, $alreadyCancelCheck);
    $checkNo = mysqli_num_rows($cancelCheckResult);
    if ($checkNo > 0) {
        echo ("Already cancelled");
        return;
        // TODO: the class has already been cancelled
    }

    // Submit the request
    $insertCancel = "INSERT INTO db_classroom_management.tbl_extra_schedule (request_type, requestor, course_id, room_id, day_of_week, slot, class_length, general_reason, message) VALUES ("
    . "'" . $requestType . "'," . $userID . "," . $courseID . "," . $roomID . "," . $dayOfWeek . "," . $slot . "," . $length . ",'" . $generalReason . "','" . $message . "');";
    $cancelResult = mysqli_query($conn, $insertCancel);
    if ($cancelResult == true)
        echo ("Request submitted");
    else {
        echo ("Not submitted");
    }
} else {
    $userID = $_POST["userID"];
    $courseID = $_POST["courseID"];
    $roomID = $_POST["roomID"];
    $dayOfWeek = $_POST["dayOfWeek"];
    $slot = $_POST["slot"];
    $length = $_POST["length"];
    // TODO: All the below tasks
    
    // Check if the day and slot (could be more than one due to length) is (or are) busy
    $normalWeekSchedule = "SELECT * FROM db_classroom_management.view_normal_schedule WHERE room_id = $roomID AND day_of_week = $dayOfWeek AND slot = $slot";
    // Compensating for length
    if ($length == 2) {
        $normalWeekSchedule = $normalWeekSchedule." OR room_id = $roomID AND day_of_week = $dayOfWeek AND slot = " . ($slot+1);
    }
    if ($length == 3) {
        $normalWeekSchedule = $normalWeekSchedule." OR room_id = $roomID AND day_of_week = $dayOfWeek AND slot = ". ($slot+2);
    }
    $normalWeekSchedule = $normalWeekSchedule . ";";

    $normalScheduleResult = mysqli_query($conn, $normalWeekSchedule);
    $normalNo = mysqli_num_rows($normalScheduleResult);

    // For extra schedule tables
    // Check if the day and slot (could be more than one due to length) is (or are) busy
    $extraWeekSchedule = "SELECT * FROM db_classroom_management.view_extra_schedule WHERE room_id = $roomID AND accept_status = 'ACCEPTED' AND day_of_week = $dayOfWeek AND slot = $slot";
    // Compensating for length
    if ($length == 2) {
        $extraWeekSchedule = $extraWeekSchedule." OR room_id = $roomID AND accept_status = 'ACCEPTED' AND day_of_week = $dayOfWeek AND slot = " . ($slot+1);
    }
    if ($length == 3) {
        $extraWeekSchedule = $extraWeekSchedule." OR room_id = $roomID AND accept_status = 'ACCEPTED' AND day_of_week = $dayOfWeek AND slot = ". ($slot+2);
    }
    $extraWeekSchedule = $extraWeekSchedule . ";";

    $extraScheduleResult = mysqli_query($conn, $extraWeekSchedule);
    $extraNo = mysqli_num_rows($extraScheduleResult);

    // TODO check form extra too
    if ($normalNo > 0 || $extraNo > 0) {
        echo "Schedule is busy";
        return;
        //TODO the day and slots are already taken
    }
    
    // Check if already requested
    $alreadyRequestCheck = "SELECT * FROM db_classroom_management.tbl_extra_schedule WHERE request_type = '$requestType' AND requestor = $userID AND course_id = $courseID AND room_id = $roomID AND day_of_week = $dayOfWeek AND slot = $slot;";
    $alreadyRequestCheckResult = mysqli_query($conn, $alreadyRequestCheck);
    $checkNo = mysqli_num_rows ($alreadyRequestCheckResult);

    if ($checkNo > 0) {
        echo "You have already requested this one.";
        return;
        //TODO the same request has already been made
    }

    // Submit request
     $insertRequest = "INSERT INTO db_classroom_management.tbl_extra_schedule (request_type, requestor, course_id, room_id, day_of_week, slot, class_length, general_reason, message) VALUES ("
    . "'" . $requestType . "'," . $userID . "," . $courseID . "," . $roomID . "," . $dayOfWeek . "," . $slot . "," . $length . ",'" . $generalReason . "','" . $message . "');";

    if (mysqli_query($conn, $insertRequest)) {
        echo "Submitted extra request.";
    } else {
        echo "Could not submit extra request.";
    }

}


?>