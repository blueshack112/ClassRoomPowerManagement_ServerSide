<?php
header("Content-type:application/json");
$_SERVER['CONTENT_TYPE'] = "application/x-www-form-urlencoded"; 


error_reporting (E_ALL ^ E_WARNING && E_NOTICE);
//error_reporting (E_ALL ^ E_NOTICE);
//Connection properties
$servername = "localhost";
$username = "areeba";
$password = "areeba";

// Create connection
$conn = new mysqli($servername, $username, $password);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

//Extract id and password from GET
$userID = $_POST["userID"];
$pass = $_POST["userPass"];


//Run query
$sqlQuery = "SELECT * FROM db_classroom_management.tbl_login_accounts where account_id=".$userID;
$result = mysqli_query($conn, $sqlQuery);

//Handle response
$numberOfRows = mysqli_num_rows($result);
$response = new \stdClass();

if ($numberOfRows > 1) {
    echo json_encode("Duplicate entries found!");
} else if ($numberOfRows == 1) {
    $row = mysqli_fetch_assoc($result);
    $response->idFound = true;
    if ($row["password"] == $pass) {
        $response->passwordCorrect = true;
    } else {
        $response->passwordCorrect = false;
    }
    $response->permissionLevel = $row["permission_level"];
} else if ($numberOfRows == 0) {
    $response->idFound = false;
}
echo json_encode($response); 
?>