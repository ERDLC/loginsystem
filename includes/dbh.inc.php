<?php

$dbServername = "localhost";

$dbUsername = "root";

$dbPassword = "";

$dbName = "loginsystem";

//$dbName = new mysqli('localhost', $dbUsername, $dbPassword, $dbName) or die("Unable to connect");

$conn = mysqli_connect($dbServername, $dbUsername, $dbPassword, $dbName);

?>