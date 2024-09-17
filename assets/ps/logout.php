<?php
session_start();
include 'db.php';

$userid = $_SESSION['user_id'];

$sql = "UPDATE users SET status = 'غير متصل' WHERE user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $userid);
$stmt->execute();

session_unset();
session_destroy();

if (isset($_COOKIE["remember"])) {
    setcookie("remember", "", time() - 3600);
}

header("location: ../../login.php");
exit;
?>