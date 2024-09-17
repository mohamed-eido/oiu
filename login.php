<?php
session_start();
include_once "assets/ps/db.php";

if (isset($_COOKIE["remember"])) {
    $_SESSION["user_id"] = $_COOKIE["remember"];
    header("Location: index.php");
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
    <link rel="stylesheet" href="assets/css/login.css" />
    <title>Login</title>
</head>
<body>
    <div class="container">
        <section class="main-content">
            <h1>OIU Community</h1>
            <form method="POST" class="input-form" id="form">
                <div class="box">
                    <input type="number" name="user_id" id="userid" class="input" placeholder="الرقم الجامعي" required />
                </div>
                <div class="box">
                    <input type="password" name="password" id="password" class="input" placeholder="كلمة المرور" required />
                </div>
                <div class="box">
                    <div class="custom-select">
                        <select name="department" id="department" class="select" required>
                            <option value="it-1">تقنية معلومات - طلاب</option>
                            <option value="is-1">نظم معلومات - طلاب</option>
                            <option value="cs-1">علوم حاسوب - طلاب</option>
                            <option value="it-2">تقنية معلومات - طالبات</option>
                            <option value="is-2">نظم معلومات - طالبات</option>
                            <option value="cs-2">علوم حاسوب - طالبات</option>
                        </select>
                    </div>
                    <div class="custom-select">
                        <select name="division" id="division" class="select" required>
                            <option value="1">الفرقة الأولى</option>
                            <option value="2">الفرقة الثانية</option>
                            <option value="3">الفرقة الثالثة</option>
                            <option value="4">الفرقة الرابعة</option>
                            <option value="5">الفرقة الخامسة</option>
                        </select>
                    </div>
                </div>
                <div class="box">
                    <input type="checkbox" name="remember" id="remember" checked />
                    <label for="remember">تذكرني</label>
                </div>
                <input type="submit" name="login" id="login" class="button" value="تسجيل الدخول" />
            </form>
            <div class="message-box message-box-error" onclick="this.style.display='none'">
            <i class="fa fa-times fa-2x"></i>
                <span class="message" id="message"></span>
            </div>
        </section>
        <div class="shapes">
            <div class="shape top"></div>
            <div class="shape bottom"></div>
        </div>
    </div>
    <script src="assets/js/login.js"></script>
</body>
</html>