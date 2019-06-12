<?php
header("Content-type:application/json");
//Data model class
class Relay {
    var $id;
    var $fans;
    function __construct($id, $fans) {
        $this->id = $id;
        $this->fans = $fans;
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

//Run query
$sqlQuery = "SELECT * FROM db_classroom_management.tbl_relays";
$result = mysqli_query($conn, $sqlQuery);


//Handle response
$numberOfRows = mysqli_num_rows($result);
$response = array();
$counter = 0;
if ($numberOfRows > 0) {
// output data of each row
    while($row = mysqli_fetch_assoc($result)) {
        $myObj = new Relay($row["relay_id"],$row["no_of_associated_fans"]);
        $response[$counter] = ($myObj);
        $counter++;
    }

} else {
 echo json_encode("0 results");
}

//Finally sending response
$jsonResponse->answers = $response;
echo json_encode($jsonResponse);
?>