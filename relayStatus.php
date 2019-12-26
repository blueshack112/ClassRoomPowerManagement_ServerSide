<?php
header("Content-type:application/json");
$_SERVER['CONTENT_TYPE'] = "application/x-www-form-urlencoded"; 
error_reporting (E_ALL ^ E_WARNING && E_NOTICE);

// Response class
class RelayStatusResponse {
    var $successful;
    var $roomActive;
    var $relaysOn;
    function __construct () {
        $this->successful = false;
        $this->roomActive = false;
        $this->relaysOn = array();
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

$roomID = $_POST['roomID'];

$getRelays = "SELECT relay_used FROM db_classroom_management.tbl_room_status WHERE room_id = $roomID";
$getRelaysResult = mysqli_query($conn, $getRelays);
$relayNo = mysqli_num_rows($getRelaysResult);

// If room status is active...
if ($relayNo > 0) {
    $response = new RelayStatusResponse();
    $response->successful = true;
    $response->roomActive = true;

    $row = mysqli_fetch_assoc($getRelaysResult);
    $relayString = $row['relay_used'];

    while (true) {
        $tempRelay = substr($relayString, 0, strpos($relayString, ","));
        // Break the loop if the string no longer has a ','
        if (!$tempRelay) {
            $response->relaysOn[] = $relayString;
            break;
        }
        $relayString = substr($relayString, strpos($relayString, ",")+1);

        // Add relay to the array
        $response->relaysOn[] = $tempRelay;
    }
    echo (json_encode($response));
} else {
    $response = new RelayStatusResponse();
    $response->successful = true;
    $response->roomActive = false;
    echo (json_encode($response));
}

?>