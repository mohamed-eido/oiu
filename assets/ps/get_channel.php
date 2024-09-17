<?php
include_once "db.php";

if (isset($_GET['id'])) {

    if ($_GET['id'] == 0) {

        $stmt = $conn->prepare("SELECT * FROM channels");
        $stmt->execute();
        $result = $stmt->get_result();
        $channel = $result->fetch_all(MYSQLI_ASSOC);
    
        // إرجاع البيانات بتنسيق JSON
        echo json_encode($channel);
    } else {

        $channelId = $_GET['id'];
    
        // جلب بيانات القناة من قاعدة البيانات
        $stmt = $conn->prepare("SELECT * FROM channels WHERE id = ?");
        $stmt->bind_param("i", $channelId);
        $stmt->execute();
        $result = $stmt->get_result();
        $channel = $result->fetch_assoc();
    
        // إرجاع البيانات بتنسيق JSON
        echo json_encode($channel);
    }
}

?>