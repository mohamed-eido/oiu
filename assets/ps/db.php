<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "test";

try {
    $conn = new mysqli($servername, $username, $password, $dbname);
} catch (\Throwable $th) {
    die("فشل الاتصال");
}