<div class="posts">
    <input type="hidden" id="user_id" value="<?php echo $_SESSION["user_id"]?>" hidden>
    <form id="createPostForm" class="create-post-form">
		<i class="fa-solid fa-times close" onclick="togglePostInput()"></i>
        <textarea id="content" class="create-post-textarea" onclick="togglePostInput()" placeholder="اكتب شيئا هنا"></textarea>
        <button type="submit" class="create-post-btn">نشر</button>
    </form>
    <div class="posts-container" id="postsContainer">
        لا توجد منشورات حاليا
    </div>
</div>