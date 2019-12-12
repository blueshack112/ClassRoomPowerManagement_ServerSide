<?php
header("Content-type:application/json");
$_SERVER['CONTENT_TYPE'] = "application/x-www-form-urlencoded"; 
error_reporting (E_ALL ^ E_WARNING && E_NOTICE);

//response class
class CheckEmailResponse {
    var $idFound;
    var $emailSent;
    function __construct () {
        $idFound = false;
        $emailSent = false;
    }
}

//Connection properties
$servername = "localhost";
$username = "root";
$password = "admin";

// Create connection
$conn = new mysqli($servername, $username, $password);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

//Extract id and password from GET
$userID = $_POST["userID"];

//Run query for login authorization
$checkEmailQuery = "SELECT *  FROM db_classroom_management.tbl_login_accounts where account_id=".$userID;
$checkEmailQueryResult = mysqli_query($conn, $checkEmailQuery);

//Handle login authorization response
$numberOfRows = mysqli_num_rows($checkEmailQueryResult);
$response = new CheckEmailResponse();

if ($numberOfRows == 1) {
    $row = mysqli_fetch_assoc($checkEmailQueryResult);
    $response->idFound = true;
    
    //Email Sending
    $to = $row["email_address"];
    $subject = "You requested the password for your account.";
         
    $message = "Hello Sir/Miss! \r\n";
    $message .= "Your account id is " . $userID . "\nYour Password is " . $row["password"] . "\r\n";
    $message .= "Kind Regards.";
         
    $header = "From:fypAreebaSabaZareen@hamdard.edu.pk \r\n";
    $header .= "MIME-Version: 1.0\r\n";
    $header .= "Content-type: text/html\r\n";
         
    $retval = mail ($to,$subject,$message,$header);
         
    if( $retval == true ) {
        $response->emailSent = true;
    } 
} else if ($numberOfRows == 0) {
    $response->idFound = false;
}

echo json_encode($response); 
?>