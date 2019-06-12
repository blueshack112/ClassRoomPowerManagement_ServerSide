<?php
header("Content-type:application/json");
$_SERVER['CONTENT_TYPE'] = "application/x-www-form-urlencoded"; 
error_reporting (E_ALL ^ E_WARNING && E_NOTICE);

//Response class
class RoomStatusResponse {
    var $successful;

    function __construct ($roomID) {
        $this->successful = false;
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

//Run query for schedule ovverride
$roomStatusQuery = "SELECT * FROM db_classroom_management.view_room_status where room_id=".$roomID;
$roomStatusQueryResult = mysqli_query($conn, $roomStatusQuery);

//Handle schedule ovverride response
$numberOfRows = mysqli_num_rows($roomStatusQueryResult);
$response = new RoomStatusResponse($roomID);


//Check if query worked properly
if ($numberOfRows >= 1) {
    $response->roomIsActive = true;
    
    
} else {
    $response->roomIsActive = false;
}

echo json_encode($response); 
?>