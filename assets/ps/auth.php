<?php
session_start();
require "db.php";

if (@$_SERVER["REQUEST_METHOD"] === "POST") {
    $userid = $_POST["user_id"];
    $password = trim($_POST["password"]);
    $remember = $_POST["remember"];

    $sql = "SELECT * FROM users WHERE user_id = ?";
    $stmt = $conn->prepare($sql);

    if ($stmt) {
        $stmt->bind_param("i", $userid);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $user = $result->fetch_assoc();
            if (sha1($password) === sha1($user["password"])) {
                $_SESSION['user_id'] = $userid;
                if ($remember) {
                    setcookie("remember", $userid, time() + (7 * 24 * 60 * 60));
                }

                $sql = "UPDATE users SET status = 'متصل' WHERE user_id = ?";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("i", $userid);
                $stmt->execute();
                $stmt->close();

                echo "success";
                exit;
            } else {
                echo "عذراً يوجد خطأ في البيانات المدخلة";
            }
        } else {
            echo "عذراً يوجد خطأ في البيانات المدخلة";
        }
        $stmt->close();
    } else {
        echo "حدث خطأ, حاول مرة أخرى";
    }
}
?>