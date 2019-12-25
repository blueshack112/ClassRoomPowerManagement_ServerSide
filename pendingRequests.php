<?php
header("Content-type:application/json");
$_SERVER['CONTENT_TYPE'] = "application/x-www-form-urlencoded"; 
error_reporting (E_ALL ^ E_WARNING && E_NOTICE);

// Pending request class
class PendingRequest {
    var $requestType;
    var $userID;
    var $userName;
    var $courseID;
    var $courseName;
    var $roomID;
    var $dayOfWeek;
    var $slot;
    var $length;
    var $generalReason;
    var $message;
    function __construct () {
        $this->requestType = "";
        $this->userID = "";
        $this->userName = "";
        $this->courseID = "";
        $this->courseName = "";
        $this->roomID = "";
        $this->dayOfWeek = "";
        $this->slot = "";
        $this->length = "";
        $this->generalReason = "";
        $this->message = "";
    }
}

// Response class
class PendingRequestResponse {
    var $successful;
    var $pendingRequests;
    var $requestsFound;
    function __construct () {
        $this->successful = false;
        $this->requestsFound = false;
        $this->pendingRequests = array();
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

// Get the pending requests using view_pending_requests
$pendingRequestsQ = "SELECT * FROM db_classroom_management.view_pending_requests;";

// Run query
$pendingRequestsResult = mysqli_query($conn, $pendingRequestsQ);
$requestNo = mysqli_num_rows($pendingRequestsResult);

// If requests are present, alot them in the array and return the array. Else, send unsuccessful response
$response = new PendingRequestResponse();
if ($requestNo > 0) {
    $response->successful = true;
    $response->requestsFound = true;

    while ($row = mysqli_fetch_assoc($pendingRequestsResult)) {
        // Load up the variables from each row
        $temp = new PendingRequest();
        $temp->requestType = $row['request_type'];
        $temp->courseID = $row['course_id'];
        $temp->courseName = $row['course_name'];
        $temp->userID = $row['requestor'];
        $temp->userName = $row['teacher_first_name'] . " " . $row['teacher_last_name'];
        $temp->roomID = $row['room_id'];
        $temp->dayOfWeek = $row['day_of_week'];
        $temp->slot = $row['slot'];
        $temp->length = $row['class_length'];
        $temp->generalReason = $row['general_reason'];
        $temp->message = $row['message'];

        // Load up the class in the array
        $response->pendingRequests[] = $temp;
    }

    echo (json_encode($response));
} else {
    // Set requestsFound variable to false and send the reply
    $response->successful = true;
    $response->requestsFound = false;
    echo (json_encode($response));
    return;
}

?>