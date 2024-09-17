
loadProfilePosts();

/////////////////////////////////////////////

function loadProfilePosts() {
    let postsContainer = document.querySelector('#profilePostsContainer');
    const xhr = new XMLHttpRequest();
    xhr.onreadystatechange = function () {
        if (xhr.readyState === 4 && xhr.status === 200) {
            postsContainer.innerHTML = xhr.responseText;
        }
    };
    xhr.open('GET', 'assets/ps/profile_data.php', true);
    xhr.send();
}