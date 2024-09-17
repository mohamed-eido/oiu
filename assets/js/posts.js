let postForm = document.querySelector("#createPostForm");
let user_id = document.querySelector("#user_id").value;

loadPosts();

postForm.onsubmit = (e) => {
    e.preventDefault();
    createPost();
}

function togglePostInput() {
    postForm.classList.toggle("active");
}

///////////////////////////////////////////////////////////////////

function toggleComments(postId) {
    const container = document.querySelectorAll(`#comments-container-${postId}`);
    container.forEach(element => {
        element.classList.toggle("active");
    });
}

/////////////////////////////////////////////

// تهيئة Pusher
const pusher = new Pusher('c5b7459597ed992dd9c9', {
    cluster: 'ap2'
});

// الاشتراك في القناة
const channel = pusher.subscribe('chat-channel');

// استقبال الرسائل الجديدة
channel.bind('new_post', function(data) {
    loadPosts(); // تحديث المشاركات عند استقبال مشاركة جديدة
    loadProfilePosts();
});

channel.bind('new_comment', function(data) {
    loadPosts(); // تحديث المشاركات عند استقبال تعليق جديد
    loadProfilePosts();
});

channel.bind('new_like', function(data) {
    loadPosts(); // تحديث المشاركات عند استقبال إعجاب جديد
    loadProfilePosts();
});

channel.bind('delete_post', function(data) {
    loadPosts();
    loadProfilePosts();
    console.log("ahaaaaaaaaaaaaa")
});

function createPost() {
    const content = postForm.querySelector("#content").value;

    if (content.trim() === '') {
        return;
    }

    const postData = {
        type: 'new_post',
        user_id: user_id, // user id
        content: content
    };

    // إرسال الطلب باستخدام AJAX
    const xhr = new XMLHttpRequest();
    xhr.open('POST', 'server.php', true);
    xhr.setRequestHeader('Content-Type', 'application/json');
    xhr.onreadystatechange = function () {
        if (xhr.readyState === 4 && xhr.status === 200) {
            postForm.classList.remove("active");
            postForm.querySelector("#content").value = "";
            loadPosts(); // إعادة تحميل المشاركات
        }
    };
    xhr.send(JSON.stringify(postData));
}

function loadPosts() {
    let postsContainer = document.querySelector('.posts #postsContainer');
    const xhr = new XMLHttpRequest();
    xhr.onreadystatechange = function () {
        if (xhr.readyState === 4 && xhr.status === 200) {
            postsContainer.innerHTML = xhr.responseText;
        }
    };
    xhr.open('GET', 'assets/ps/get_posts.php', true);
    xhr.send();
}

function deletePost(postId) {
    let postData = {
        type: 'delete_post',
        post_id: postId,
    };

    let xhr = new XMLHttpRequest();
    xhr.open('POST', 'server.php', true);
    xhr.setRequestHeader('Content-Type', 'application/json');
    xhr.send(JSON.stringify(postData));
}

function likePost(postId) {
    const likeData = {
        type: 'new_like',
        post_id: postId,
        user_id: user_id // user id
    };

    // إرسال الإعجاب باستخدام AJAX
    const xhr = new XMLHttpRequest();
    xhr.open('POST', 'server.php', true);
    xhr.setRequestHeader('Content-Type', 'application/json');
    xhr.send(JSON.stringify(likeData));
}

function addComment(postId) {
    const content = document.getElementById(`commentContent-${postId}`).value;

    if (content.trim() === '') {
        return;
    }

    const commentData = {
        type: 'new_comment',
        post_id: postId,
        user_id: user_id, // user id
        content: content
    };

    // إرسال التعليق باستخدام AJAX
    const xhr = new XMLHttpRequest();
    xhr.open('POST', 'server.php', true);
    xhr.setRequestHeader('Content-Type', 'application/json');
    xhr.send(JSON.stringify(commentData));
}
