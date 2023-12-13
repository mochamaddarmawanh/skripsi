<?php

$server_name = "localhost";
$username = "root";
$password = "";
$database_name = "face_recognition_2";

$connection = new mysqli($server_name, $username, $password, $database_name);

if ($connection->connect_error) {
    die("Connection failed: " . $connection->connect_error);
}
