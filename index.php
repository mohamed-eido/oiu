<?php
session_start();
include_once "assets/ps/db.php";

if (isset($_COOKIE["remember"])) {
    $_SESSION["user_id"] = $_COOKIE["remember"];
}

if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <script crossorigin="anonymous" src="https://kit.fontawesome.com/05ddc1d5ba.js"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Baloo+Bhaijaan+2:wght@400..800&display=swap" />
    <link rel="stylesheet" href="assets/css/main.css" />
    <link rel="stylesheet" href="assets/css/posts.css" />
    <link rel="stylesheet" href="assets/css/chats.css" />
    <link rel="stylesheet" href="assets/css/bot.css" />
    <link rel="stylesheet" href="assets/css/files.css" />
    <link rel="stylesheet" href="assets/css/profile.css" />
    <title>OIU Community</title>
</head>
<body>
    <div class="container" id="blur">
        <div class="spinner loaded">
            <img alt="spinner" src="assets/images/spinner.svg">
        </div>
        <header class="header">
            <h1 class="title">الرئيسية</h1>
            <i class="fa-solid fa-right-from-bracket" id="logout" onclick="toggle()"></i>
        </header>
        <div class="content" id="content">
            <section class="home active"><?php include_once("posts.php");?></section>
            <section class="chats"><?php include_once("chats.php");?></section>
            <section class="bot"><?php include_once("bot.php");?></section>
            <section class="files"><?php include_once("files.php");?></section>
            <section class="user profile"><?php include_once("profile.php");?></section>
        </div>
        <nav class="nav">
            <div class="btn home active" custom="الرئيسية">
                <i class="fa-solid fa-house"></i>
            </div>
            <div class="btn chats" custom="الدردشات">
                <i class="fa-solid fa-message"></i>
            </div>
            <div class="btn bot" custom="المساعد الرقمي">
                <i class="fa-solid fa-burst"></i>
            </div>
            <div class="btn files" custom="المحاضرات">
                <i class="fa-solid fa-file-lines"></i>
            </div>
            <div class="btn user" custom="الملف الشخصي">
                <i class="fa-solid fa-user"></i>
            </div>
            <span class="bar"></span>
        </nav>
    </div>
    <div class="popup">
        <p>هل تريد تسجيل الخروج؟</p>
        <button onclick="logout()">تسجيل الخروج</button>
        <button onclick="toggle()">إغلاق</button>
    </div>
	<script src="https://js.pusher.com/8.2.0/pusher.min.js"></script>
    <script src="assets/js/main.js"></script>
    <script src="assets/js/posts.js"></script>
    <script src="assets/js/chats.js"></script>
    <script src="assets/js/bot.js"></script>
    <script src="assets/js/files.js"></script>
    <script src="assets/js/profile.js"></script>
</body>
</html>