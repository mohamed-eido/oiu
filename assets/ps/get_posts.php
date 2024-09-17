<?php
require 'db.php';

$sql = "SELECT posts.id, posts.content, users.username, posts.created_at, 
        (SELECT COUNT(*) FROM likes WHERE likes.post_id = posts.id) as likes_count 
        FROM posts 
        JOIN users ON posts.user_id = users.id 
        ORDER BY posts.created_at DESC";

$result = $conn->query($sql);

$posts = array();
while ($row = $result->fetch_assoc()) {
    $post_id = $row['id'];
    
    $comment_sql = "SELECT comments.content, users.username, comments.created_at 
                    FROM comments 
                    JOIN users ON comments.user_id = users.id 
                    WHERE comments.post_id = $post_id 
                    ORDER BY comments.created_at ASC";
    $comment_result = $conn->query($comment_sql);

    $comments = array();
    while ($comment_row = $comment_result->fetch_assoc()) {
        $comments[] = $comment_row;
    }

    $row['comments'] = $comments;
    $posts[] = $row;
}

$conn->close();

foreach ($posts as $post) {
    echo "
        <div class='post'>
            <div class='post-header'>
                <div class='profile-pic'>
					<i class='fa-solid fa-user'></i>
				</div>
                <div class='user-info'>
                    <h3 class='username'>{$post['username']}</h3>
                    <span class='post-date'>{$post['created_at']}</span>
                </div>
            </div>
            <p class='post-content'>{$post['content']}</p>
            <div class='post-footer'>
                <button class='like-btn' onclick='likePost({$post['id']})'><span class='likes-count'>{$post['likes_count']}</span> إعجاب</button>
                <button class='comment-btn' onclick='toggleComments({$post['id']})'>التعليقات</button>
            </div>
            <div class='comments-container' id='comments-container-{$post['id']}'>
                <i class='fa-solid fa-times close' onclick='toggleComments({$post['id']})'></i>
            ";
            foreach ($post['comments'] as $comment) {
                echo "
                <div class='comment'>
                    <div class='profile-pic'>
                        <i class='fa-solid fa-user'></i>
                    </div>
                    <div class='comment-info'>
                        <h4 class='comment-username'>{$comment['username']}</h4>
                        <span class='comment-date'>{$comment['created_at']}</span>
                        <p class='comment-content'>{$comment['content']}</p>
                    </div>
                </div>
                ";
            }

            echo "
                <div class='comment-box'>
                    <textarea id='commentContent-{$post['id']}' placeholder='اكتب تعليقا...'></textarea>
                    <button class='post-comment-btn' onclick='addComment({$post['id']})'>تعليق</button>
                </div>
            </div>
        </div>";
}
?>