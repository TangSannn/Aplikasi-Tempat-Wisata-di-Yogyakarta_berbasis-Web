<?php
$host = "localhost"; // atau localhost:3307
$user = "root";
$pass = "";
$db   = "wisatajogja1";

$conn = mysqli_connect($host, $user, $pass, $db);


if (!$conn) {
    die("DB ERROR: " . mysqli_connect_error());
}