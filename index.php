<?php

$servername = "localhost";
$username = "root";
$password = "0000";
$dbname = "dbpro";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}else{
    echo "Connected successfully to " .$dbname;
}

?>