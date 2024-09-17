const form = document.querySelector("#form");
const button = document.querySelector("#login");
const message = document.querySelector("#message");

form.addEventListener("submit", (e) => {
    e.preventDefault();

    button.classList.add("clicked");

    let userid = document.querySelector("#userid").value;
    let password = document.querySelector("#password").value;
    let remember = document.querySelector("#remember");

    const xhr = new XMLHttpRequest();
    xhr.onreadystatechange = () => {
        if (xhr.readyState === 4 && xhr.status === 200) {
            if (xhr.responseText === "success") {
                window.location.href = "index.php";
            } else {
                message.innerHTML = xhr.responseText;
            }
        }
    }
    xhr.open("POST", "assets/ps/auth.php", true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    const params = `user_id=${userid}&password=${password}&remember=${remember.checked ? "1" : "0"}`;
    xhr.send(params);

    // مراقب عشان يظهر الدالة لما تتغير
    const observer = new MutationObserver((e) => {
        message.parentElement.style.display="flex";
        button.classList.remove("clicked");
    });

    observer.observe(message, {childList: true});
});