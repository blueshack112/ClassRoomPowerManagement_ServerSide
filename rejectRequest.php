<?php
header("Content-type:application/json");
$_SERVER['CONTENT_TYPE'] = "application/x-www-form-urlencoded";
error_reporting(E_ALL ^ E_WARNING && E_NOTICE);

//Response class
class RejectRequestResponse
{
    var $successful;
    var $errorCode;
    var $emailed;
    function __construct()
    {
        $this->emailed = false;
        $this->successful = false;
        $this->errorCode = "";
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

// call the update query
$rejectQuery = "UPDATE db_classroom_management.tbl_extra_schedule SET accept_status = 'REJECTED' WHERE room_id = $roomID AND requestor = $userID AND course_id = $courseID AND day_of_week = $dayOfWeek AND slot = $slot AND class_length = $length AND request_type = '$requestType';";
$rejectQueryResult = mysqli_query($conn, $rejectQuery);

if ($rejectQueryResult) {
    $response = new RejectRequestResponse();
    $response->successful = true;
    $response->errorCode = "none";

    // Email the user
    //Run query for login authorization
    $checkEmailQuery = "SELECT *  FROM db_classroom_management.tbl_login_accounts where account_id = $userID";
    $checkEmailQueryResult = mysqli_query($conn, $checkEmailQuery);

    //Handle login authorization response
    $numberOfRows = mysqli_num_rows($checkEmailQueryResult);

    if ($numberOfRows == 1) {
        $row = mysqli_fetch_assoc($checkEmailQueryResult);

        // Converting day of week from 1-5 to Monday-Friday
        $day = "";
        switch ($dayOfWeek) {
            case 1:
                $day = "Monday";
                break;
            case 2:
                $day = "Tuesday";
                break;
            case 3:
                $day = "Wednesday";
                break;
            case 4:
                $day = "Thursday";
                break;
            case 5:
                $day = "Friday";
                break;
        }

        //Email Sending
        $to = $row["email_address"];
        $subject = "Follow up for your cancel request.";

        $message = "Hello Sir/Miss! \r\n";
        if ($requestType == "EXTRA") {
            $message .= "You recently requested an extra class in Room $roomID, on $day, in slot $slot for $length credit hour(s).\r\n";
        } else {
            $message .= "You recently requested to cancel a class in Room $roomID, on $day, in slot $slot for $length credit hour(s).\r\n";
        }
        $message .= "We would like to update you that your request has been rejected and no changes in the schedule have been made.\r\n";
        $message .= "Kind Regards.";

        $header = "From:fypAreebaSabaZareen@hamdard.edu.pk \r\n";
        $header .= "MIME-Version: 1.0\r\n";
        $header .= "Content-type: text/html\r\n";

        $retval = mail($to, $subject, $message, $header);

        if ($retval == true) {
            $response->emailed = true;
        }
    }
    echo (json_encode($response));
    return;
} else {
    $response = new RejectRequestResponse();
    $response->successful = false;
    $response->errorCode = "unknown";
    $response->emailed = false;
    echo (json_encode($response));
    return;
}
?>