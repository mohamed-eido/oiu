<?php
require 'vendor/autoload.php';
include_once "assets/ps/db.php"; // هذا الملف يجب أن يحتوي على معلومات الاتصال بقاعدة البيانات

$options = array(
    'cluster' => 'ap2',
    'useTLS' => true
);

$pusher = new Pusher\Pusher(
    'c5b7459597ed992dd9c9',
    'ec0a8fdc3cae0d5cdf21',
    '1861637',
    $options
);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);

    switch ($data['type']) {
        case 'new_post':
            handleNewPost($data, $conn, $pusher);
            break;
        case 'delete_post':
            handleDeletePost($data, $conn, $pusher);
            break;
        case 'new_comment':
            handleNewComment($data, $conn, $pusher);
            break;
        case 'new_like':
            handleNewLike($data, $conn, $pusher);
            break;
        case 'sendMessages':
            handleSendMessages($data, $conn, $pusher);
            break;
        case 'fetchMessages':
            handleFetchMessages($data, $conn);
            break;
    }
}

function handleNewPost($data, $conn, $pusher) {
    $stmt = $conn->prepare("INSERT INTO posts (user_id, content) VALUES (?, ?)");
    $stmt->bind_param("is", $data['user_id'], $data['content']);
    $stmt->execute();
    $data['post_id'] = $stmt->insert_id;
    $stmt->close();

    $pusher->trigger('chat-channel', 'new_post', $data);
}

function handleDeletePost($data, $conn, $pusher) {
    $stmt = $conn->prepare("DELETE FROM comments WHERE post_id = ?");
    $stmt->bind_param("i", $data['post_id']);
    $stmt->execute();
    $stmt->close();

    $stmt = $conn->prepare("DELETE FROM likes WHERE post_id = ?");
    $stmt->bind_param("i", $data['post_id']);
    $stmt->execute();
    $stmt->close();

    $stmt = $conn->prepare("DELETE FROM posts WHERE id = ?");
    $stmt->bind_param("i", $data['post_id']);
    $stmt->execute();
    $stmt->close();

    $pusher->trigger('chat-channel', 'delete_post', $data);
}

function handleNewComment($data, $conn, $pusher) {
    $stmt = $conn->prepare("INSERT INTO comments (post_id, user_id, content) VALUES (?, ?, ?)");
    $stmt->bind_param("iis", $data['post_id'], $data['user_id'], $data['content']);
    $stmt->execute();
    $data['comment_id'] = $stmt->insert_id;
    $stmt->close();

    $pusher->trigger('chat-channel', 'new_comment', $data);
}

function handleNewLike($data, $conn, $pusher) {
    // تحقق مما إذا كان المستخدم قد قام بالإعجاب مسبقًا
    $stmt = $conn->prepare("SELECT id FROM likes WHERE post_id = ? AND user_id = ?");
    $stmt->bind_param("ii", $data['post_id'], $data['user_id']);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        // المستخدم قد قام بالإعجاب مسبقًا، لذا نقوم بإلغاء الإعجاب
        $stmt->close();
        $stmt = $conn->prepare("DELETE FROM likes WHERE post_id = ? AND user_id = ?");
        $stmt->bind_param("ii", $data['post_id'], $data['user_id']);
        $stmt->execute();
        $stmt->close();

        // ترسل حدث "إلغاء الإعجاب" إلى Pusher أو أي قناة أخرى
        $pusher->trigger('chat-channel', 'new_like', $data);
        
    } else {
        // المستخدم لم يقم بالإعجاب، لذا نقوم بإضافة إعجاب جديد
        $stmt->close();
        $stmt = $conn->prepare("INSERT INTO likes (post_id, user_id) VALUES (?, ?)");
        $stmt->bind_param("ii", $data['post_id'], $data['user_id']);
        $stmt->execute();
        $stmt->close();

        // ترسل حدث "إضافة الإعجاب" إلى Pusher أو أي قناة أخرى
        $pusher->trigger('chat-channel', 'new_like', $data);
    }
}

function handleSendMessages($data, $conn, $pusher) {
    $senderId = $data['senderId'];
    $receiverId = $data['receiverId'];
    $messageText = $data['message'];

    $stmt = $conn->prepare("INSERT INTO messages (sender, receiver, message) VALUES (?, ?, ?)");
    $stmt->bind_param("iis", $senderId, $receiverId, $messageText);
    $stmt->execute();
    $stmt->close();

    $pusher->trigger('chat-channel', 'new_message', [
        'senderId' => $senderId,
        'receiverId' => $receiverId,
        'message' => $messageText
    ]);
}

function handleFetchMessages($data, $conn) {
    $senderId = $data['senderId'];
    $receiverId = $data['receiverId'];

    $result = $conn->query("SELECT * FROM messages WHERE (sender = $senderId AND receiver = $receiverId) OR (sender = $receiverId AND receiver = $senderId) ORDER BY id");

    $messages = [];
    while ($row = $result->fetch_assoc()) {
        $messages[] = $row;
    }

    $result->close();

    echo json_encode($messages);
}

?>