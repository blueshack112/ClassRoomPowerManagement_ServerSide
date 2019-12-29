<?php
header("Content-type:application/json");
$_SERVER['CONTENT_TYPE'] = "application/x-www-form-urlencoded";
error_reporting(E_ALL ^ E_WARNING && E_NOTICE);

// Response class
class OverrideResponse {
    var $successful;
    var $errorCode;
    function __construct () {
        $this->successful = false;
        $this->errorCode = "unknown";
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

// Read data from Post
$userID = $_POST['userID'];
$roomID = $_POST['roomID'];
$day = $_POST['day'];
$slot = $_POST['slot'];
$length = $_POST['length'];
$relays = array();
$relays[] = $_POST['relay1'];
$relays[] = $_POST['relay2'];
$relays[] = $_POST['relay3'];
$relays[] = $_POST['relay4'];
$relays[] = $_POST['relay5'];
$relays[] = $_POST['relay6'];
$relays[] = $_POST['relay7'];
$relays[] = $_POST['relay8'];



// Create an array of the requested override and calculate how many slots of the day will be taken
$slotsNeeded = [0,0,0,0,0,0,0];
$slotsUsed = [0,0,0,0,0,0,0];
$normalClassesToCancel = array();
$extraClassesToCancel = array();

for ($i = 0; $i < $length; $i++) {
    $slotsNeeded[($slot-1) + $i] = 1;
}

// Call every schedule item on that day (Normal)
$normalSchedule = "SELECT schedule_id, slot, class_length FROM db_classroom_management.view_normal_schedule WHERE room_id = $roomID AND day_of_week = $day";
$normalScheduleResult = mysqli_query($conn, $normalSchedule);

// Calculate which schedule item's slots are clashing with the request
while ($row = mysqli_fetch_assoc($normalScheduleResult)) {
    $tempS = $row['slot'];
    $tempL = $row['class_length'];
    $tempSched = $row['schedule_id'];

    // Check if they coincide
    $coincide = false;
    if ($slotsNeeded[$tempS -1] == 1) {
        $coincide = true;
    } else if ($tempL == 2 && $slotsNeeded[$tempS] == 1) {
        $coincide = true;
    } else if ($tempL == 3 && ($slotsNeeded[$tempS] == 1 || $slotsNeeded[$tempS+1] == 1)) {
        $coincide = true;
    }

    // If coinciding, add the class to classes that need to be cancelled
    if ($coincide) {
        $normalClassesToCancel[] = $tempSched;
    }
}

// Call every schedule item on that day (Extra)
$extraSchedule = "SELECT extra_schedule_id, slot, class_length FROM db_classroom_management.view_extra_schedule WHERE room_id = $roomID AND day_of_week = $day";
$extraScheduleResult = mysqli_query($conn, $extraSchedule);

// Calculate which schedule item's slots are clashing with the request
while ($row = mysqli_fetch_assoc($extraScheduleResult)) {
    $tempS = $row['slot'];
    $tempL = $row['class_length'];
    $tempSched = $row['extra_schedule_id'];

    // Check if they coincide
    $coincide = false;
    if ($slotsNeeded[$tempS -1] == 1) {
        $coincide = true;
    } else if ($tempL == 2 && $slotsNeeded[$tempS] == 1) {
        $coincide = true;
    } else if ($tempL == 3 && ($slotsNeeded[$tempS] == 1 || $slotsNeeded[$tempS+1] == 1)) {
        $coincide = true;
    }

    // If coinciding, add the class to classes that need to be cancelled
    if ($coincide) {
        $extraClassesToCancel[] = $tempSched;
    }
}

// Cancel all the coinciding classes (Normal)
for ($i = 0; $i < count($normalClassesToCancel); $i++) {
    $cancelQuery = "DELETE FROM db_classroom_management.tbl_week_schedule WHERE schedule_id = " . $normalClassesToCancel[$i];
    $cancelQueryResult = mysqli_query($conn, $cancelQuery);

    if (!$cancelQuery) {
        $response = new OverrideResponse();
        echo (json_encode($response));
        return;
    }
}

// Cancel all the coinciding classes (Extra)
for ($i = 0; $i < count($extraClassesToCancel); $i++) {
    $cancelQuery = "DELETE FROM db_classroom_management.tbl_extra_schedule WHERE extra_schedule_id = " . $extraClassesToCancel[$i];
    $cancelQueryResult = mysqli_query($conn, $cancelQuery);

    if (!$cancelQuery) {
        $response = new OverrideResponse();
        echo (json_encode($response));
        return;
    }
}

// Add the override query in extra schedule table
// Now that all of the classes have been cancelled. Let's update the extra schedule table

// Calculate relays to turn on
$relaysToTurnOn = "";
for ($i = 0; $i < count($relays); $i++) {
    if ($relays[$i] == 'true') {
        $relaysToTurnOn .= "10" . ($i+1) . ",";
    }
}
$relaysToTurnOn = substr($relaysToTurnOn, 0, strlen($relaysToTurnOn)-1);

// The insert query
$insertQuery = "INSERT INTO db_classroom_management.tbl_extra_schedule (accept_status, request_type, requestor, room_id, day_of_week, slot, class_length, general_reason, message) VALUES ('ACCEPTED', 'HOD', $userID, $roomID, $day, $slot, $length, 'Highest priority request!', '$relaysToTurnOn');";
$insertQueryResult = mysqli_query($conn, $insertQuery);

if ($insertQueryResult) {
    // Set success status and echo it
    $response = new OverrideResponse();
    $response->successful = true;
    echo (json_encode($response));
    return;
} else {
    // Set error status and echo it
    $response = new OverrideResponse();
    $response->errorCode = $insertQuery;
    echo (json_encode($response));
    return;
}
?>