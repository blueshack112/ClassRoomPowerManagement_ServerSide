<?php
header("Content-type:application/json");
$_SERVER['CONTENT_TYPE'] = "application/x-www-form-urlencoded"; 
error_reporting (E_ALL ^ E_WARNING && E_NOTICE);

//Response class
class ExtraRequestResponse {
    var $successful;
    var $errorCode;
    function __construct () {
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
$userID = $_POST["userID"];
$courseID = $_POST["courseID"];
$roomID = $_POST["roomID"];
$dayOfWeek = $_POST["dayOfWeek"];
$slot = $_POST["slot"];
$length = $_POST["length"];

// Call the slots and lengths of each class that is schedule in the same room for the same day
$extraScheduleSlots = "SELECT slot, class_length FROM db_classroom_management.tbl_extra_schedule WHERE room_id = $roomID AND day_of_week = $dayOfWeek AND request_type = '$requestType' AND accept_status = 'ACCEPTED'" ;
$extraScheduleSlotsResult = mysqli_query($conn, $extraScheduleSlots);
$slotsNo = mysqli_num_rows($extraScheduleSlotsResult);

// If not result came, the reques is ready to GO!
if (!$slotsNo > 0) {
    //TODO update the extraschedule table and weekschedule table
}

while ($row = mysqli_fetch_assoc($extraScheduleSlotsResult)) {
    echo (json_encode($row));
}

?>