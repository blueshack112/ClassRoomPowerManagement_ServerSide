<?php
header("Content-type:application/json");
$_SERVER['CONTENT_TYPE'] = "application/x-www-form-urlencoded";
error_reporting(E_ALL ^ E_WARNING && E_NOTICE);

//Response class
class AcceptRequestResponse
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

$busySlots = [0, 0, 0, 0, 0, 0, 0];
if ($requestType == "EXTRA") {
    // Call the slots and lengths of each class that is schedule in the same room for the same day
    $extraScheduleSlots = "SELECT slot, class_length FROM db_classroom_management.tbl_extra_schedule WHERE room_id = $roomID AND day_of_week = $dayOfWeek AND request_type = 'EXTRA' AND accept_status = 'ACCEPTED'";
    $extraScheduleSlotsResult = mysqli_query($conn, $extraScheduleSlots);
    $slotsNo = mysqli_num_rows($extraScheduleSlotsResult);
    while ($row = mysqli_fetch_assoc($extraScheduleSlotsResult)) {
        $tempSlot = $row['slot'];
        $tempLength = $row['class_length'];

        $busySlots[$tempSlot - 1] = 1;
        if ($tempLength == 2) {
            $busySlots[$tempSlot] = 1;
        }
        if ($tempLength == 3) {
            $busySlots[$tempSlot] = 1;
            $busySlots[$tempSlot + 1] = 1;
        }
    }

    // Now check if the current slot and length combo coincides with the already accepted ones
    $coincides = false;
    if ($busySlots[$slot - 1] == 1) {
        $coincides = true;
    } else if ($length == 2 && $busySlots[$slot] == 1) {
        $coincides = true;
    } else if ($length == 3 && ($busySlots[$slot] == 1 || $busySlots[$slot + 1] == 1)) {
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
            $message .= "You recently requested an extra class in Room $roomID, on $day, in slot $slot for $length credit hour(s)\r\n";
            $message .= "We would like to update you that your request has been accepted.\r\n";
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
                $message .= "You recently requested to cancel your class held in in Room $roomID, on $day, in slot $slot for $length credit hour(s)\r\n";
                $message .= "We would like to update you that your class has been cancelled.\r\n";
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