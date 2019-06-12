<?php
header("Content-type:application/json");
$_SERVER['CONTENT_TYPE'] = "application/x-www-form-urlencoded"; 
error_reporting (E_ALL ^ E_WARNING && E_NOTICE);

//response class
class AuthResponse {
    var $idFound;
    var $passwordCorrect;
    var $permissionLevel;
    var $holderFirstName;
    var $holderLastName;
    var $holderDesignation;

    function __construct () {
        $idFound = false;
        $passwordCorrect = false;
        $permissionLevel = "";
        $holderFirstName = "";
        $holderLastName = "";
        $holderDesignation = "";      
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
$pass = $_POST["userPass"];

//Run query for login authorization
$loginAccountsQuery = "SELECT * FROM db_classroom_management.tbl_login_accounts where account_id=".$userID;
$loginAccountsQueryResult = mysqli_query($conn, $loginAccountsQuery);

//Handle login authorization response
$numberOfRows = mysqli_num_rows($loginAccountsQueryResult);
$response = new AuthResponse();

if ($numberOfRows == 1) {
    $row = mysqli_fetch_assoc($loginAccountsQueryResult);
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

//Run query for account details account found
if ($response->idFound == true) {
    $teacherQuery = "SELECT * FROM db_classroom_management.tbl_teachers where teacher_id=".$userID;
    $teacherQueryResult = mysqli_query($conn, $teacherQuery);
    if ($numberOfRows == 1) {
        $teacherRow = mysqli_fetch_assoc($teacherQueryResult);
        $response->holderFirstName = $teacherRow["teacher_first_name"];
        $response->holderLastName = $teacherRow["teacher_last_name"];
        $response->holderDesignation = $teacherRow["teacher_designation"];
    }
}

echo json_encode($response); 
?>