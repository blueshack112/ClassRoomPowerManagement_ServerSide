<?php
header("Content-type:application/json");
$_SERVER['CONTENT_TYPE'] = "application/x-www-form-urlencoded";
error_reporting(E_ALL ^ E_WARNING && E_NOTICE);

//Response class
class AcceptRequestResponse
{
    var $successful;
    var $errorCode;
    function __construct()
    {
        $this->successful = false;
        $this->errorCode = "";
        // TODO: you have already accepted a request for the same day slot
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

//cold initialization
$requestType = $_POST["requestType"];
$roomID = $_POST["roomID"];
$userID = $_POST["userID"];
$courseID = $_POST["courseID"];
$dayOfWeek = $_POST["dayOfWeek"];
$slot = $_POST["slot"];
$length = $_POST["length"];

$busySlots = [0,0,0,0,0,0,0];
if ($requestType == "EXTRA") {
    // Call the slots and lengths of each class that is schedule in the same room for the same day
    $extraScheduleSlots = "SELECT slot, class_length FROM db_classroom_management.tbl_extra_schedule WHERE room_id = $roomID AND day_of_week = $dayOfWeek AND request_type = 'EXTRA' AND accept_status = 'ACCEPTED'";
    $extraScheduleSlotsResult = mysqli_query($conn, $extraScheduleSlots);
    $slotsNo = mysqli_num_rows($extraScheduleSlotsResult);
    while ($row = mysqli_fetch_assoc($extraScheduleSlotsResult)) {
        $tempSlot = $row['slot'];
        $tempLength = $row['class_length'];

        $busySlots[$tempSlot-1] = 1;
        if ($tempLength == 2) {
            $busySlots[$tempSlot] = 1;
        }
        if ($tempLength == 3) {
            $busySlots[$tempSlot] = 1;
            $busySlots[$tempSlot+1] = 1;            
        }
    }

    // Now check if the current slot and length combo coincides with the already accepted ones
    $coincides = false;
    if ($busySlots[$slot-1] == 1) {
        $coincides = true;
    } else if ($length == 2 && $busySlots[$slot] == 1){
        $coincides = true;
    }
    else if ($length == 3 && ($busySlots[$slot] == 1 || $busySlots[$slot+1] == 1)) {
        $coincides = true;
    }

    // If the timing coincides, then a class has already been accepted
    if ($coincides) {
        // Set the error message and echo it
        $response = new AcceptRequestResponse();
        $response->successful = false;
        $response->errorCode = "alreadyaccepted";
        echo (json_encode($response));
        return;
    }

    // No error, so udpate the extra request table
    $updateExtraQuery = "UPDATE db_classroom_management.tbl_extra_schedule SET accept_status = 'ACCEPTED' WHERE room_id = $roomID AND course_id = $courseID AND day_of_week = $dayOfWeek AND slot = $slot AND request_type = 'EXTRA'";
    $updateResult = mysqli_query($conn, $updateExtraQuery);
    if ($updateResult) {
        // Set the success message and echo it
        $response = new AcceptRequestResponse();
        $response->successful = true;
        $response->errorCode = "none";
        echo (json_encode($response));
        // TODO: email the user
        return;
    } else {
        // Set the error message and echo it
        $response = new AcceptRequestResponse();
        $response->successful = false;
        $response->errorCode = "unknown";
        echo (json_encode($response));
        return;
    }
} else {
    // Find the id of this entry in week schedule table
    $getScheduleID = "SELECT schedule_id FROM db_classroom_management.tbl_schedule WHERE room_id = $roomID AND day_of_week = $dayOfWeek AND slot = $slot";
    $getScheduleIDResult = mysqli_query($conn, $getScheduleID);
    
    // Retrieve the ID from result
    $temp = mysqli_fetch_assoc($getScheduleIDResult);
    $scheduleID = $temp['schedule_id'];

    // Delete the entry from week schedule table
    $deleteQuery = "DELETE FROM db_classroom_management.tbl_week_schedule WHERE schedule_id = $scheduleID";
    $deleteQueryResult = mysqli_query($conn, $deleteQuery);

    // if the record was successfully deleted, run the update query
    if ($deleteQueryResult) {
        $updateCancelQuery = "UPDATE db_classroom_management.tbl_extra_schedule SET accept_status = 'ACCEPTED' WHERE room_id = $roomID AND day_of_week = $dayOfWeek AND slot = $slot AND request_type = 'CANCEL'";
        $updateCancelQueryResult = mysqli_query($conn, $updateCancelQuery);

        if ($updateCancelQuery) {
            // Set the success message and echo it
            $response = new AcceptRequestResponse();
            $response->successful = true;
            $response->errorCode = "none";
            echo (json_encode($response));
            // TODO: Email User
            return;
        }
    } else {
        // Set the error message and echo it
        $response = new AcceptRequestResponse();
        $response->successful = false;
        $response->errorCode = "unknown";
        echo (json_encode($response));
        return;
    }
}
?>