<?php

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "QL_NhanSu";

$conn = new mysqli($servername, $username, $password, $dbname);   

if($conn->connect_error){
    die("Kết nối database thất bại " . $conn->connect_error);
}