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

$roomID = $_POST['roomID'];



?>