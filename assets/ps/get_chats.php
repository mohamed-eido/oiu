<?php
    session_start();

    if (isset($_SESSION["user_id"])) {
        include_once "db.php";

		$userId = $_SESSION['user_id'];
        @$searchTerm = '%' . $_POST['searchTerm'] . '%';
        $output = "";
    
        $stmt = $conn->prepare("SELECT * FROM users WHERE NOT user_id = ? AND username LIKE ?");
        $stmt->bind_param("is", $userId, $searchTerm);
        $stmt->execute();
        $result = $stmt->get_result();
    
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $stmt2 = $conn->prepare("SELECT * FROM messages WHERE (receiver = ? OR sender = ?) AND (sender = ? OR receiver = ?) ORDER BY id DESC LIMIT 1");
                $stmt2->bind_param("iiii", $row['user_id'], $row['user_id'], $userId, $userId);
                $stmt2->execute();
                $result2 = $stmt2->get_result();
                $row2 = $result2->fetch_assoc();
    
                $data = ($result2->num_rows > 0) ? $row2["message"] : "لا توجد رسائل";
                $msg = (strlen($data) > 50) ? substr($data, 0, 50) . "..." : $data;
                $you = isset($row2["sender"]) && $userId == $row2["sender"] ? "أنت: " : "";
    
                $status = $row["status"] == "متصل" ? "متصل الآن" : "غير متصل";
    
                $output .= "<a href='message.php?user_id=".$row["user_id"]."'>
                                <div class='user'>
                                    <div class='img' custom='".$row["img"]."'>
                                        <i class='fa-solid fa-user'></i>
                                    </div>
                                    <div class='details'>
                                        <span>".$row["username"]."</span>
                                        <p>".$you.$msg."</p>
                                    </div>
                                </div>
                                <div class='status-dot ".$status."'><i class='fas fa-circle'></i></div>
                            </a>";
            }
        } else {
            $output .= "لم يتم العثور على هذا المستخدم";
        }
        echo $output;
    } else {
        header("location: ../../login.php");
    }
?>